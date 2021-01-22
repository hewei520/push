<?php


namespace app\lib\jpush;


use JPush\Client;
use think\facade\Env;
use think\facade\Log;


/**
 * @file Common
 * @synopsis jpush
 * @author hewei(hewei@xiniaogongkao.com)
 * @version 1.0.0
 * @date 2021/1/14
 */
class Jpush extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cidSet($cid = "")
    {
       self::setCid($cid);
       return $this;
    }

    public function aliasAdd($alias = [])
    {
        self::addAlias($alias);
        return $this;
    }

    public function registrationIdAdd($regID = [])
    {
        self::addRegistrationId($regID);
        return $this;
    }

    public function notificationAndroid($alert, $title = "", $builderID = 0, $extras = [])
    {
        self::androidNotification($alert, $title, $builderID, $extras);
        return $this;
    }

    public function notificationIos($alert, $sound = "", $badge = 0, $extras = [])
    {
        self::iosNotification($alert, $sound, $badge, $extras);
        return $this;
    }

    public function messageSet($content = "", $title = "", $extras = [])
    {
        self::setMessage($content, $title, $extras);
        return $this;
    }

    public function push()
    {
       return self::send();
    }

}