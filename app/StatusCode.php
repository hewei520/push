<?php
/**
 * 状态码定义类
 * User: 威少
 * Date: 2021/1/20
 * Time: 10:47
 */

namespace app;


class StatusCode
{
    // 公共状态码
    const SUCCESS   = 200;
    const FAIL      = 300;
    const ERROR     = 500;

    // 极光推送状态码
    const JP_CODE_ERROR = 1000; // code不合法
}