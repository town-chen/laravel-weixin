<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/weui/dist/style/weui.min.css">
</head>
<body>
<div class="container">
    <article class="weui_article">
        <h1>课程</h1>
    </article>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_bd">
            @foreach($courses as $course)
                <a href="{{ url('wechat/login/course',$course->id) }}" class="weui_media_box weui_media_appmsg">
                    <div class="weui_media_bd">
                        <h4 class="weui_media_title">{{ $course->name }}</h4>
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <button id="preview" class="weui_btn weui_btn_mini weui_btn_primary">预览</button>
    <button id="location" class="weui_btn weui_btn_mini weui_btn_primary">地理位置</button>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(['onMenuShareTimeline', 'previewImage', 'getLocation']) ?>);

    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: '微信课程', // 分享标题
            link: 'http://xuexi.jikexueyuan.com/course/2.html', // 分享链接
            imgUrl: 'http://m2.quanjing.com/2m/fod021/fod-987262.jpg', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                alert('您增加了1积分');
                // ajax
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });

    $('#preview').click(function () {
        wx.previewImage({
            current: 'http://m2.quanjing.com/2m/fod021/fod-987262.jpg', // 当前显示图片的http链接
            urls: [
                'http://m2.quanjing.com/2m/fod021/fod-987262.jpg',
                'http://pic.qiantucdn.com/58pic/19/43/68/56d3e7ffb7957_1024.jpg'
            ] // 需要预览的图片http链接列表
        });
    });

    $('#location').click(function(){
        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                console.log(latitude);
                console.log(longitude);
                console.log(speed);
                console.log(accuracy);
            }
        });
    });
</script>
</html>