<?php
namespace app\controller;

use app\BaseController;
use app\lib\jpush\Jpush as JpushLib;
use app\lib\jpush\JpushStatic;
use think\facade\Log;

class Jpush extends BaseController
{
    public function push()
    {
//        $regId = ['100d855909f5342e530'];
//        var_dump(JpushStatic::push($regId,[JpushStatic::PLATFORM_ANDROID],"欢迎来到犀鸟公考","犀鸟通知"));
//
//        $jpush = new JpushLib();
//        var_dump($jpush->registrationIdAdd($regId)->notificationAndroid("欢迎来到犀鸟公考2","犀鸟通知")->push());
        print_r(lang("success"));
//        return response(["msg" => lang("success")],200);
        Log::info("success OK!!!");
    }
}
