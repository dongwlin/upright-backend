<?php
// 这是系统自动生成的公共文件

use think\response\Json;

if (!function_exists('api_result')) {
    /**
     * 返回json格式的api结果
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_result(string $msg = 'ok', array $data = [], int $code = 200): Json
    {
        if (is_array($data) && empty($data))
        {
            $data = (object)$data;
        }
        $header = [];
        // http code是否同步业务code
        $http_code = config('api.response.http_code_sync') ? $code : 200;

        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ], $http_code, $header);
    }
}

if (!function_exists('api_ok')) {
    /**
     * ok
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return Json
     */
    function api_ok(array $data = [], string $msg = 'ok', int $code = 200): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_created')) {
    /**
     * created
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_created(string $msg = 'created', array $data = [], int $code = 201): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_accepted')) {
    /**
     * accepted
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_accepted(string $msg = 'accepted', array $data = [], int $code = 202): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_not_content')) {
    /**
     * not content
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_accepted(string $msg = 'not content', array $data = [], int $code = 204): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_bad_request')) {
    /**
     * bad request
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_bad_request(string $msg = 'bad request', array $data = [], int $code = 400): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_unauthorized')) {
    /**
     * unauthorized
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_unauthorized(string $msg = 'unauthorized', array $data = [], int $code = 401): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_forbidden')) {
    /**
     * forbidden
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_forbidden(string $msg = 'forbidden', array $data = [], int $code = 403): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_not_found')) {
    /**
     * not found
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_not_found(string $msg = 'not found', array $data = [], int $code = 404): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_method_not_allowed')) {
    /**
     * method not allowed
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_method_not_allowed(string $msg = 'method not allowed', array $data = [], int $code = 405): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_unsupported_media_type')) {
    /**
     * unsupported media type
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Json
     */
    function api_unsupported_media_type(string $msg = 'unsupported media type', array $data = [], int $code = 415): Json
    {
        return api_result($msg, $data, $code);
    }
}