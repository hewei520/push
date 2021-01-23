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

if (!function_exists('getOrderNumber')) {
// 获取订单号, 18位长度：2位标识 + 10位年月日时 + 5位随机 + 1位校验码
    function getOrderNumber($orderNumber)
    {
        // 时间
        $orderNumber .= date("YmdH");

        $orderNumber .= getRandomString(5);

        // 加校验码
        $orderNumber .= getCheckCode($orderNumber);

        return $orderNumber;
    }
}

if (!function_exists('getRandomString')) {
    /**
     * 随机码生成方法
     * @param $len integer 长度
     * @return string
     */
    function getRandomString($len)
    {
        $str    = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y"];
        $result = "";
        for ($i = 0; $i < $len; $i++) {
            $result .= $str[rand(0, count($str) - 1)];
        }
        return $result;
    }
}

if (!function_exists('checkOrderNumber')) {
    /**
     * 校验单号
     * @param $orderNumber string 单号
     * @return bool
     */
    function checkOrderNumber($orderNumber)
    {
        // 判空
        if ($orderNumber == "") {
            return false;
        }

        //获取最后一位校验码
        $strOldC = substr($orderNumber, -1);
        if ($strOldC == "") {
            return false;
        }

        //剔除最后一位的校验码
        $strC = substr($orderNumber, 0, 17);
        if ($strC == "") {
            return false;
        }

        //核算校验码
        $strNewC = getCheckCode($strC);

        //判断校验码是否正确
        if ($strNewC === $strOldC) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('getCheckCode')) {
    /**
     * 单号校验码生成
     * @param $code string 单号
     * @return string
     */
    function getCheckCode($code)
    {
        //位数对应乘积码表
        $arrM    = [3, 1, 6, 5, 8, 4, 2, 1, 6, 3, 7, 9, 1, 5, 8];
        $codeNew = substr($code, 2);
        $codeArr = str_split($codeNew);
        $intS    = 0;
        foreach ($codeArr as $key => $value) {
            $intS += (int)$value * $arrM[$key];
        }

        //除数，常识除数不能设置为0
        $intD = 11;

        //除以除数取余strconv.Itoa()
        $intCheckCode = $intS % $intD;

        if ($intCheckCode == 10) {
            $strCheckCode = "X";
        } else {
            $strCheckCode = "" . $intCheckCode;
        }

        return $strCheckCode;
    }
}

//if (!function_exists('getUrl')) {
//    /**
//     * GET请求
//     * @param $url string 请求地址
//     * @return bool
//     */
//    function getUrl($url)
//    {
//        $info = curl_init();
//        curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($info, CURLOPT_HEADER, 0);
//        curl_setopt($info, CURLOPT_NOBODY, 0);
//        curl_setopt($info, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($info, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($info, CURLOPT_URL, $url);
//        $output = curl_exec($info);
//        curl_close($info);
//        return true;
//    }
//}
//
//if (!function_exists('postUrl')) {
//    /**
//     * POST请求
//     * @param $url string 请求地址
//     * @param $postData array 参数
//     * @return bool
//     */
//    function postUrl($url, $postData = [])
//    {
//        if (empty($url) || empty($postData)) {
//            return false;
//        }
//
//        $o = "";
//        foreach ($postData as $k => $v) {
//            $o .= "$k=" . urlencode($v) . "&";
//        }
//        $postData = substr($o, 0, -1);
//        $postUrl  = $url;
//        $curlPost = $postData;
//        $ch       = curl_init();//初始化curl
//        curl_setopt($ch, CURLOPT_URL, $postUrl);//抓取指定网页
//        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
//        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
//        $data = curl_exec($ch);//运行curl
//        curl_close($ch);
//
//        return $data;
//    }
//}