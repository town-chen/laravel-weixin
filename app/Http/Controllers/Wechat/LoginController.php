<?php

namespace App\Http\Controllers\Wechat;

use App\Models\Course;
use App\Http\Controllers\Controller;
use EasyWeChat\Payment\Order;
use Wechat\Card\Card;

class LoginController extends Controller
{
    public function getCourse($id)
    {
        $user = session('wechat_user');

        $course = Course::findOrFail($id);

        return view('login.course', compact('course', 'user'));
    }

    public function getCard()
    {
        $wechat = app('wechat');
        $js = $wechat->js;

        $card = new Card($wechat->access_token);

        // 创建卡券的js配置信息
        $cardJson = $card->configForCard('plWVUtzQps-gBRplG4U1_0PG3NN4');

        return view('login.card', compact('js', 'cardJson'));
    }

    public function getName()
    {
        $user = session('wechat_user');

        return '欢迎你 ' . $user['nickname'];
    }
}
