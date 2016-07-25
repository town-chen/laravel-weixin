<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/weui/dist/style/weui.min.css">
</head>
<body>
<div class="container">
    <button id="card" class="weui_btn weui_btn_mini weui_btn_primary">领取卡券</button>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(['addCard']) ?>);

    $('#card').click(function () {
        wx.addCard({
            cardList: [ {!! json_encode($cardJson) !!} ], // 需要添加的卡券列表
            success: function (res) {
                var cardList = res.cardList; // 添加的卡券列表信息
            }
        });
    });
</script>
</html>