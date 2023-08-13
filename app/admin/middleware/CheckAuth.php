<?php
declare (strict_types = 1);

namespace app\admin\middleware;

use Closure;
use think\facade\Session;
use think\Request;
use think\response\Redirect;

class CheckAuth
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure       $next
     * @return Redirect
     */
    public function handle(Request $request, Closure $next)
    {
        $isLogin = Session::get('isLogin', false);
        $url = $request->baseUrl();
        $urlWhiteList = ['/admin/login', '/admin/captcha', '/admin/login/auth'];
        if ($isLogin) {
            if ($url == '/admin/login')
            {
                return redirect('/admin');
            }
            return $next($request);
        }
        if (in_array($url, $urlWhiteList)) {
            return $next($request);
        }
        return redirect('/admin/login');
    }
}
