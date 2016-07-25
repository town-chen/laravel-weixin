<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use App\Models\WechatTempQr;
use Illuminate\Http\Request;
use Wechat\Card\Card;

class TestController extends Controller
{
    // 生成临时二维码
    public function getQrcode(Request $request)
    {
        $wechat = app('wechat');
        $qrcode = $wechat->qrcode;

        // 绑定user_id=10微信
        $qr = WechatTempQr::create(['type' => 'bind_wechat', 'params' => ['user_id' => 10]]);

        $result = $qrcode->temporary($qr->id);

        $url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片

        $qrCode = new \Endroid\QrCode\QrCode();
        header('Content-Type: image/png');
        $qrCode
            ->setText($url)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('low')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabelFontSize(16)
            ->render();
        die;
    }

    // 模板消息
    public function getTemplate()
    {
        $wechat = app('wechat');
        $notice = $wechat->notice;

        $messageId = $notice->send([
            'touser' => 'olWVUt_-PPUgukB_1RxveimqUYeM',
            'template_id' => 'QKVukpFBqSR4VWtoTIPfbA34gHFPWEFM5AKPtXCxT64',
            'url' => 'xuexi.jikexueyuan.com/course/2.html',
            'topcolor' => '#FF0000',
            'data' => [
                'userName' => 'TimJuly',
                'courseName' => '微信开发',
                'date' => '2016-4-13 20:30',
                'remark' => "\nlajfkl12j3kl1j1klj31kl2jk12j1kl212j3k12j1kl2j3kl12jk1l2j1kl2j31lkj23QKVukpFBqSR4VWtoTIPfbA34gHFPWEFM5AKPtXCxT64QKVukpFBqSR4VWtoTIPfbA34gHFPWEFM5AKPtXCxT64QKVukpFBqSR4VWtoTIPfbA34gHFPWEFM5AKPtXCxT64",
            ],
        ]);

        return $messageId;
    }

    public function getCard()
    {
        $wechat = app('wechat');
        $card = new Card($wechat->access_token);

        $data = [
            "card_type" => "CASH",
            "cash" => [
                'base_info' => [
                    'logo_url' => 'http://s1.jikexueyuan.com/common/images/logo_c8caff4.png',
                    'code_type' => 'CODE_TYPE_TEXT',
                    'brand_name' => '极客学院微信PHP开发班',
                    'title' => '100元抵用券',
                    'color' => 'Color010',
                    'notice' => '请在购买前输入验证码来使用',
                    'description' => '100元抵用券',
                    'sku' => [
                        'quantity' => 10,
                    ],
                    'date_info' => [
                        'type' => 'DATE_TYPE_FIX_TIME_RANGE',
                        'begin_timestamp' => strtotime('2016-01-01 00:00:00'),
                        'end_timestamp' => strtotime('2016-12-31 23:59:59'),
                    ],
                ],
                'least_cost' => 20000,
                'reduce_cost' => 10000,
            ],
        ];
        $res = $card->create($data);

        return $res;

        // plWVUtzQps-gBRplG4U1_0PG3NN4
    }

    public function getCardWhiteList()
    {
        $wechat = app('wechat');

        $card = new Card($wechat->access_token);

        $res = $card->setWhiteList([
            'username' => [
                'dd_52033',
                't_july',
            ],
        ]);

        var_dump($res);
    }

    public function getCardConsume()
    {
        // 535488907233
        $wechat = app('wechat');

        $card = new Card($wechat->access_token);

        $res = $card->cardConsume('535488907233');
        var_dump($res);
    }

    public function getMenuSet()
    {
        $wechat = app('wechat');
        $menu = $wechat->menu;

        $buttons = [
            [
                "type" => "click",
                "name" => "最新课程",
                "key" => "new_course",
            ],
            [
                "name" => "二级菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "微信开发课程",
                        "url" => "http://xuexi.jikexueyuan.com/course/2.html",
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD",
                    ],
                ],
            ],
            [
                "type" => "view",
                "name" => "课程列表",
                "url" => "http://wechat.jikexueyuan.com/wechat/notlogin/course",
            ],
        ];
        $res = $menu->add($buttons);

        return $res;
    }

    public function getUploadPic()
    {
        $wechat = app('wechat');
        $material = $wechat->material;

        $result = $material->uploadImage("/home/vagrant/jike/W020160417681681249090.jpg");
        var_dump($result);
    }

    public function getStats()
    {
        $wechat = app('wechat');
        $stats = $wechat->stats;

        $res = $stats->userSummary('2016-04-11', '2016-04-16');

        return $res;
    }
}