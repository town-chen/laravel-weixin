<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Config;

class WechatLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = session('wechat_user');
        // 判断用户是否登陆
        if (empty($user)) {
            // 判断是否是微信跳转回来的页面
            $code = $request->input('code');
            if ( ! empty($code)) {
                // 用户登陆
                $http = new Client();
                $response = $http->get('https://api.weixin.qq.com/sns/oauth2/access_token', [
                    'query' => [
                        'appid' => Config::get('wechat.app_id'),
                        'secret' => Config::get('wechat.secret'),
                        'code' => $code,
                        'grant_type' => 'authorization_code',
                    ],
                ]);

                $json = json_decode($response->getBody()->getContents(), true);

                if ( ! empty($json['openid'])) {
                    $userInfo = $http->get('https://api.weixin.qq.com/sns/userinfo', [
                        'query' => [
                            'access_token' => $json['access_token'],
                            'openid' => $json['openid'],
                            'lang' => 'zh_CN',
                        ],
                    ]);

                    $userInfo = json_decode($userInfo->getBody()->getContents(), true);
                    // openid 去数据库查找对应用户
                    // $user = User::find(1);
                    // if ($user) {
                    // 如果找到绑定的用户,登陆
                        session()->put('wechat_user', $userInfo);
                        $url = $request->url();
                        $url = $url . '/';
                        $query = $request->except(['code', 'state']);
                        if ( ! empty($query)) {
                            $url = $url . '?' . http_build_query($query);
                        }
                        return \Redirect::to($url);
                    // }
                }
            }

            // 跳转到微信登陆页面

            $params = [
                'appid' => Config::get('wechat.app_id'),
                'redirect_uri' => $request->url(),
                'response_type' => 'code',
                'scope' => 'snsapi_userinfo',
                'state' => time(),
            ];

            return \Redirect::to('https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($params));
        }

        return $next($request);
    }
}
