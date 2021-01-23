<?php
/**
 * 发送极光推送进程
 * User: 威少
 * Date: 2021/1/21
 * Time: 15:05
 */

namespace app\command\jpush;

use app\lib\jpush\JpushStatic;
use app\model\jpush\JpushRecord;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;
use hw\Post;

class Push extends Command
{
    protected function configure()
    {
        $this->setName('jpush_push');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->info("jpush_push start");

        while (true) {
            $list = $this->getList();

            if (empty($list)) {
                Log::close();
                continue;
            }

            // 修改状态为发送中
            JpushRecord::update(["status" => JpushRecord::STATUS_SENDING], ["id" => $list["id"]]);

            $ok = JpushStatic::push(
                $this->registrationIdsCarding($list["registration_ids"]),
                $this->aliasCarding($list["alias"]),
                $this->platformCarding($list["platform"]),
                $list["alert"],
                $list["title"],
                $list["extras"]
            );

            if (!$ok) {
                Log::info("jpush_push msg:push fail! id=" . $list["id"]);
                // 修改状态为发送失败
                JpushRecord::update(["status" => JpushRecord::STATUS_FAIL], ["id" => $list["id"]]);
                continue;
            }
            // 修改状态为发送成功
            JpushRecord::update(["status" => JpushRecord::STATUS_SUCCESS], ["id" => $list["id"]]);
            Log::info("jpush_push msg:push success! id=" . $list["id"]);

            // 如果有回调地址，通知回调地址
            if (!empty($list["callback_url"])){
                Post::postUrl(
                    $list["callback_url"],
                    [
                        "code"              => $list["code"],
                        "status"            => JpushRecord::STATUS_SUCCESS,
                        "msg"               => $list["msg"],
                        "extras"            => $list["extras"],
                    ]
                );
            }
        }

        $output->info("jpush_push end");
    }

    // 获取数据
    protected function getList(){
        $list = JpushRecord::where(["status" => JpushRecord::STATUS_WAIT])
            ->whereTime('send_at', '<=', time())->find();
        return $list ? $list->toArray() : [];
    }

    // 整理接收平台
    protected function platformCarding($platform){
        if (empty($platform)) {
            return [];
        }

        $platformArr = json_decode($platform, true);
        if (!is_array($platformArr)){
            $platformArr = [$platform];
        }

        return $platformArr;
    }

    // 整理注册码
    protected function registrationIdsCarding($registrationIds){
        if (empty($registrationIds)) {
            return [];
        }

        $registrationIdsArr = json_decode($registrationIds, true);
        if (!is_array($registrationIdsArr)){
            $registrationIdsArr = [$registrationIds];
        }

        return $registrationIdsArr;
    }

    // 整理别名
    protected function aliasCarding($alias){
        if (empty($alias)) {
            return [];
        }

        $aliasArr = json_decode($alias, true);
        if (!is_array($aliasArr)){
            $aliasArr = [$alias];
        }

        return $aliasArr;
    }
}