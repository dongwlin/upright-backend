<?php
declare (strict_types = 1);

namespace app\api\middleware;

use app\api\service\TokenService;
use app\common\model\UserModel;

class CheckAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return \think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        $auth = $request->header('authorization');
        if ($auth == null)
        {
            return api_unauthorized();
        }
        $service = new TokenService();
        $res = $service->checkToken($auth);
        if ($res['code'] == 1 && array_key_exists('openid', $res) && !empty($res['openid']))
        {
            $openid = $res['openid'];
            $user = UserModel::where('openid', $openid)->findOrEmpty();
            if ($user->isEmpty())
            {
                return api_error('user not exists');
            }
            else if (!$user->status)
            {
                return api_forbidden();
            }
            $request->openid = $openid;
        }
        else
        {
            return api_unauthorized();
        }
        return $next($request);
    }
}
