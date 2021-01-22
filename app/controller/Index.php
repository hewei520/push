<?php
namespace app\controller;

use app\BaseController;
use app\lib\jpush\Jpush;
use app\lib\jpush\JpushStatic;

class Index extends BaseController
{
    public function index()
    {
        $regId = ['100d855909f5342e530'];
        var_dump(JpushStatic::push($regId,[JpushStatic::PLATFORM_ANDROID],"欢迎来到犀鸟公考","犀鸟通知"));

        $jpush = new Jpush();
        var_dump($jpush->registrationIdAdd($regId)->notificationAndroid("欢迎来到犀鸟公考2","犀鸟通知")->push());
    }
}
