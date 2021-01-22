<?php
namespace app\controller;

use app\BaseController;
use app\model\jpush\JpushRecord;
use app\StatusCode;
use think\facade\Log;

class Jpush extends BaseController
{
    /**
     * 发送推送
     * @return \think\response\Json
     */
    public function push()
    {
        if (!$this->request->isPost()){
            return returnErrorJson(StatusCode::ERROR,lang("notIsPost"));
        }

        $post = $this->request->post();

        $rule =   [
            'platform'  => 'require',
            'type'      => 'require|in:' . JpushRecord::TYPE_ALIAS . ',' . JpushRecord::TYPE_REGISTRATION,
            'alert'     => 'require',
            'delayed'   => 'integer'
        ];
        $validate = \think\facade\Validate::rule($rule);
        if(!$validate->check($post)){
            return returnErrorJson(StatusCode::ERROR,$validate->getError());
        }

        if ($post["type"] == JpushRecord::TYPE_REGISTRATION && empty($post["registration_ids"])) {
            return returnErrorJson(StatusCode::ERROR, "registration_ids必填");
        }

        if ($post["type"] == JpushRecord::TYPE_ALIAS && empty($post["alias"])){
            return returnErrorJson(StatusCode::ERROR, "alias必填");
        }

        $platform = json_decode($post["platform"], true);
        if (is_array($platform)){
            $platformNew = array_intersect($platform,JpushRecord::platformList());
            if (count($platformNew) < count($platform)){
                return returnErrorJson(StatusCode::ERROR, "platform 只能填" . json_encode(JpushRecord::platformList()) . "内");
            }
        }else{
            if (!in_array($post["platform"], JpushRecord::platformList())) {
                return returnErrorJson(StatusCode::ERROR, "platform 只能填" . json_encode(JpushRecord::platformList()) . "内");
            }
        }

        $jr = new JpushRecord();

        $delayed = !empty($post["delayed"]) ? $post["delayed"] : 0;
        $ok = $jr->save([
            "platform"          => $post["platform"],
            "code"              => getOrderNumber("JP"),
            "type"              => $post["type"],
            "status"            => JpushRecord::STATUS_WAIT,
            "registration_ids"  => $post["registration_ids"] ?: "",
            "alias"             => $post["alias"] ?? "",
            "alert"             => $post["alert"],
            "title"             => $post["title"] ?? "",
            "extras"            => $post["extras"] ?? "",
            "callback_url"      => $post["callback_url"] ?? "",
            "send_at"           => date("Y-m-d H:i:s", time() + $delayed)
        ]);

        if (!$ok) {
            return returnErrorJson(500,lang("error"));
        }

        return returnSuccessJson(["code" => $jr->code]);
    }

    /**
     * 查询发送结果
     */
    public function query(){
        if (!$this->request->isGet()){
            return returnErrorJson(StatusCode::ERROR, lang("notIsGet"));
        }

        $get = $this->request->get();

        $rule =   [
            'code'  => 'require',
        ];

        $validate = \think\facade\Validate::rule($rule);
        if(!$validate->check($get)){
            return returnErrorJson(StatusCode::ERROR,$validate->getError());
        }

        // 验证code是否合法
        if (!checkOrderNumber($get["code"])){
            return returnErrorJson(StatusCode::JP_CODE_ERROR,lang("codeError"));
        }

        // 查询状态
        $field = "code,platform,type,status,registration_ids,alias,alert,title,extras";
        $info = JpushRecord::where(["code" => $get["code"]])->field($field)->find();
        if (!$info) {
            return returnErrorJson(StatusCode::JP_CODE_ERROR,lang("codeError"));
        }

        return returnSuccessJson($info);
    }

    public function callback(){
        Log::info("callback params=" . json_encode($this->request->post()));
    }
}
