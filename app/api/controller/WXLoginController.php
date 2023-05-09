<?php
/**
 * WX Login Controller
 */
declare(strict_types=1);

namespace app\api\controller;

use app\api\exception\ApiServiceException;
use app\common\model\User as UserModel;
use app\api\service\TokenService;
use think\response\Json;

class WXLoginController
{
    protected string $appId;

    protected string $appSecret;

    protected string $grantType;

    public function __construct()
    {
        $this->appId = config('wxmini.app_id');
        $this->appSecret = config('wxmini.app_secret');
        $this->grantType = config('wxmini.grant_type');
    }

    public function index(TokenService $service): Json
    {
        if (!request()->has('code'))
        {
            return api_error('missing code');
        }
        $jsCode = request()->param('code');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appId}&secret={$this->appSecret}&js_code={$jsCode}&grant_type={$this->grantType}";
        $result = $this->getSession($url);

        if (array_key_exists('errcode' , $result))
        {
            return api_error($result['errmsg'], ['errcode' => $result['errcode']]);
        }

        if (array_key_exists('openid', $result))
        {
            $data = UserModel::where('openid', '=', $result['openid'])->findOrEmpty();
            if ($data->isEmpty())
            {
                $data = UserModel::create([
                    'openid' => $result['openid'],
                    'nickname' => '微信用户',
                    'gender' => -1,
                    'avatar' => request()->host() . '/res/image/1'
                ]);
            }
            try {
                return api_success([
                   'token' => $service->createToken($result['openid']),
                    'nickname' => $data->nickname,
                    'gender' => $data->gender,
                    'avatar' => $data->avatar
                ]);
            }
            catch (ApiServiceException $exception)
            {
                return api_error($exception->getMessage());
            }
        }
        return api_error();
    }

    /**
     * 获取session
     * @param $url
     * @return mixed
     */
    protected function getSession($url)
    {
        $headerArray =array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

}