<?php

namespace App\Http\Controllers\Api\V1\Wechat;

use App\Models\WechatTempQr;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Link;
use EasyWeChat\Message\News;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function server(Request $request)
    {
        $wechat = app('wechat');
        $server = $wechat->server;

        $server->setMessageHandler(function ($message) use ($wechat) {
            Log::info(json_encode($message));

            $messageType = $message['MsgType'];
            switch ($messageType) {
                case 'text':
                    return '1234567890';
                    break;
                case 'event':
                    $eventType = $message['Event'];
                    switch ($eventType) {
                        case 'SCAN':
                            $eventKey = $message['EventKey'];
                            if (empty($eventKey)) {
                                return;
                            }
                            $qr = WechatTempQr::find($eventKey);
                            switch ($qr->type) {
                                case 'bind_wechat':
                                    $user_id = $qr->params['user_id'];

                                    $userService = $wechat->user;
                                    $userInfo = $userService->get($message['FromUserName']);

                                    Log::info($userInfo->nickname . '和用户系统的' . $user_id . '绑定微信成功');
                                    break;
                            }
                            break;
                        case
                        'subscribe':
                            Log::info('订阅人数+1');
                            // 处理详细的二维码扫描事件

                            break;
                        case 'unsubscribe':
                            // 微信账号解绑
                            // 订阅人数-1
                            Log::info('订阅人数-1');
                            break;

                        case 'LOCATION':
                            // 把用户所在地存到数据库
                            break;

                        // 用户点击菜单
                        case 'CLICK':
                            $eventKey = $message['EventKey'];
                            switch ($eventKey) {
                                case 'new_course':
                                    // return '最新课程是使用php开发微信';
                                    // return new Image(['media_id' => 'tSfLAg8ZKPCQP5ApgIvMOtoA6tSzk57lierlTjfsx2E']);
                                    // return 'http://wechat.jikexueyuan.com/wechat/login/card';
                                    return 'http://wechat.jikexueyuan.com/wechat/notlogin/course';
                                    break;
                                case 'V1001_GOOD':
                                    $news = new News([
                                        'title' => '赞一下',
                                        'description' => '谢谢您刚才攒了一下我们',
                                        'url' => 'http://xuexi.jikexueyuan.com/course/2.html',
                                        'image' => 'http://jiuye-res.jikexueyuan.com/xuexi/course/attach-aecf7cb4-eb80-4326-a54a-bb6995578ee8.jpg',
                                    ]);

                                    return $news;
                                    break;
                            }
                            break;
                    }
                    break;
            }
        });

        return $server->serve();
    }


    public function paymentCallback(Request $request)
    {
        Log::info($request->getContent());

        return 'success';
    }
}

