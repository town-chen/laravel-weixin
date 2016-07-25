<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use App\Models\Course;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use Wechat\Card\Card;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('wechat.login', ['only' => ['getIndex']]);
    }

    public function getIndex(Request $request)
    {
        $user = session('wechat_user');

        $wechat = app('wechat');
        $js = $wechat->js;
        $payment = $wechat->payment;

        $id = $request->course_id;
        $course = Course::find($id);

        $price = 1000;

        /*$coupon = $request->coupon;
        if(!empty($coupon)){
            // 卡券核销
            $card = new Card($wechat->access_token);

            $res = $card->cardConsume($coupon);
            if($res['errcode'] === 0){
                // 价格调整
                $price = 1;
            }
        }*/
        $price = 1;

        $attributes = [
            'body' => $course->name,
            'detail' => '使用PHP进行微信开发',
            'out_trade_no' => time(),
            'total_fee' => $price,
            'notify_url' => 'http://jikewechat.proxy.qqbrowser.cc/api/1/wechat/payment-callback',
            'trade_type' => 'JSAPI',
            'openid' => $user['openid'],
        ];

        $order = new Order($attributes);

        $result = $payment->prepare($order);
        $prepayId = $result->prepay_id;

        $json = $payment->configForPayment($prepayId);

        return view('payment.index', compact('js', 'json'));
    }

    public function getQr()
    {
        $wechat = app('wechat');
        $payment = $wechat->payment;

        $attributes = [
            'body' => '微信课程',
            'detail' => '使用PHP进行微信开发',
            'out_trade_no' => time(),
            'total_fee' => 1,
            'notify_url' => 'http://jikewechat.proxy.qqbrowser.cc/api/1/wechat/payment-callback',
            'trade_type' => 'NATIVE',
        ];

        $order = new Order($attributes);

        $result = $payment->prepare($order);

        $qrCode = new \Endroid\QrCode\QrCode();
        header('Content-Type: image/png');
        $qrCode
            ->setText($result['code_url'])
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('low')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabelFontSize(16)
            ->render();
        die;
    }


}