<?php
namespace app\controller;

use app\BaseController;
use app\model\jpush\JpushRecord;
use app\StatusCode;

class Jpush extends BaseController
{
    public function push()
    {
        if (!$this->request->isPost()){
            return returnErrorJson(StatusCode::ERROR,lang("notIsPost"));
        }

        $post = $this->request->post();

        return returnSuccessJson($post);
        $jr = new JpushRecord();
        $jr->save([
            "platform"          => json_encode([JpushRecord::PLATFORM_IOS]),
            "type"              => JpushRecord::TYPE_REGISTRATION,
            "status"            => JpushRecord::STATUS_WAIT,
            "registration_ids"  => json_encode(["dfjodjglj5","kgjlghh4563"]),
            "alert"             => "测试通知"
        ]);
        sleep(2);
        JpushRecord::update(["status" => JpushRecord::STATUS_SENDING],["id" => $jr->id]);

        JpushRecord::destroy($jr->id);
        return returnErrorJson(500,lang("error"),$jr->select()->toArray());
    }
}
