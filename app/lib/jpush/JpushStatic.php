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
class JpushStatic extends Common
{
    public static function push($regId = [], $platform = [], $alert = "", $title = "", $extras = [], $builderID = 0,$badge = 0)
    {
        self::init();
        self::addRegistrationId($regId);
        if (in_array(self::PLATFORM_ANDROID, $platform)){
            self::androidNotification($alert, $title, $builderID, $extras);
        }

        if (in_array(self::PLATFORM_IOS, $platform)){
            self::iosNotification($alert, $title, $badge, $extras);
        }

        return self::send();
    }
}