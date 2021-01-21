<?php
// 应用公共文件

if (!function_exists('returnSuccessJson')) {
    /**
     * 返回成功方法（json）
     * @param array $data 返回的数据
     * @param string $msg 返回信息
     * @param array $header 头部
     * @return \think\response\Json
     */
    function returnSuccessJson($data = [], $msg = "", $header = [])
    {
        $info = [
            "code"   => \app\StatusCode::SUCCESS,
            "msg"    => $msg ?: lang("success"),
            "date"   => date("Y-m-d H:i:s"),
            "data"   => $data ?: (object)[]
        ];
        return json($info,\app\StatusCode::SUCCESS, $header);
    }
}

if (!function_exists('returnErrorJson')) {
    /**
     * 返回成功方法（json）
     * @param int $code 返回的数据
     * @param array $data 返回的数据
     * @param string $msg 返回信息
     * @param array $header 头部
     * @return \think\response\Json
     */
    function returnErrorJson($code, $msg, $data = [], $header = [])
    {
        $info = [
            "code"   => $code,
            "msg"    => $msg,
            "date"   => date("Y-m-d H:i:s"),
            "data"   => $data ?: (object)[]
        ];
        return json($info,\app\StatusCode::SUCCESS, $header);
    }
}