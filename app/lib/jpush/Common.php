<?php


namespace app\lib\jpush;


use JPush\Client;
use think\facade\Env;
use think\facade\Log;

define("JPUSH_LOG_PATH", APP_PATH."/runtime/log/jpush");

/**
 * @file Common
 * @synopsis jpush
 * @author hewei(hewei@xiniaogongkao.com)
 * @version 1.0.0
 * @date 2021/1/14
 */
class Common
{
    const PLATFORM_ANDROID  = "android";
    const PLATFORM_IOS      = "ios";
    const PLATFOEM_WINPHONE = "winphone";

    protected static $appKey = "";
    protected static $secret = "";
    protected static $jpush = null;
    protected static $platform = [];

    protected function __construct()
    {
        self::init();
    }

    protected static function init()
    {
        self::$appKey = Env::get("JPUSH.appKey");
        self::$secret = Env::get("JPUSH.secret");

        $client = new Client(self::$appKey, self::$secret, JPUSH_LOG_PATH);

        $ok = $client != null ? "success" : "fail";
        Log::info("jpush client config:appKey=".self::$appKey." secret=".self::$secret. " jpush=". $ok);

        self::$jpush = $client->push();
    }



    protected static function setCid($cid = "")
    {
        if (!empty($cid)) {
            self::$jpush->setCid($cid);
        }
    }

    protected static function addRegistrationId($regID = [])
    {
        self::$jpush->addRegistrationId($regID);
    }

    protected static function androidNotification($alert, $title = "", $builderID = 0, $extras = [])
    {
        array_push(self::$platform, self::PLATFORM_ANDROID);
        $android_notification = [];
        if (!empty($title)) $android_notification["title"] = $title;
        if (!empty($builderID)) $android_notification["builder_id"] = $builderID;
        if (!empty($extras)) $android_notification["extras"] = $extras;

        self::$jpush->androidNotification($alert, $android_notification);
    }

    protected static function iosNotification($alert, $sound = "", $badge = 0, $extras = [])
    {
        array_push(self::$platform, self::PLATFORM_IOS);

        $ios_notification = ["content-available" => true, "category" => "jiguang"];
        if (!empty($sound)) $ios_notification["sound"] = $sound;
        if (!empty($badge)) $ios_notification["badge"] = $badge;
        if (!empty($extras)) $ios_notification["extras"] = $extras;

        self::$jpush->iosNotification($alert, $ios_notification);
    }

    protected static function setMessage($content = "", $title = "", $extras = [])
    {
        $message = [
            'title'         => $title,
            'content_type'  => 'text',
            'extras'        => $extras,
        ];
        self::$jpush->message($content, $message);
    }

    protected static function send()
    {
        $response = self::$jpush->setPlatform(self::$platform)->send();
        if (!empty($response["http_code"]) && $response["http_code"] == 200){
            return true;
        }

        return false;
    }

}