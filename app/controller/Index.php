<?php
namespace app\controller;

use app\BaseController;
use app\lib\jpush\Jpush;
use app\lib\jpush\JpushStatic;

class Index extends BaseController
{
    public function index()
    {
        $service = new \Yar_Client("http://120.24.215.14:4010/jpush_rpc");
        $service->SetOpt(YAR_OPT_PACKAGER,'php');
        return $service->query(["code" => "gf23d1g32fd1g"]);
    }
}
