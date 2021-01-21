<?php
/**
 * 极光推送信息记录表
 * User: 威少
 * Date: 2021/1/20
 * Time: 11:37
 */

namespace app\model\jpush;

use app\lib\jpush\Common;
use app\model\CommonModel;

class JpushRecord extends CommonModel
{
    // 类型常量
    const TYPE_ALIAS        = "alias";          // 别名推送
    const TYPE_REGISTRATION = "registration";   // 注册ID推送

    // 接收平台常量
    const PLATFORM_ANDROID  = Common::PLATFORM_ANDROID; // 安卓
    const PLATFORM_IOS      = Common::PLATFORM_IOS;     // 苹果

    // 状态常量
    const STATUS_WAIT       = "wait";       // 等待发送
    const STATUS_SUCCESS    = "success";    // 发送成功
    const STATUS_FAIL       = "fail";       // 发送失败
    const STATUS_SENDING    = "sending";    // 发送中

}