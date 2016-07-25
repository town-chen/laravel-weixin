<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/weui/dist/style/weui.min.css">
</head>
<body>
<div class="container">
    <h1>欢迎你 {{ $user['nickname'] }}</h1>
    <div>
        <article class="weui_article">
            <h1>课程{{ $course->id }}</h1>
            <section>
                <section>
                    <h3>{{ $course->name }}</h3>
                </section>
                <p>
                    课程价格: 10元
                </p>
                <a href="{{ url('wechat/payment') }}/?course_id={{ $course->id }}&coupon=3883928348234" class="weui_btn weui_btn_mini weui_btn_primary">购买</a>
            </section>
        </article>
    </div>
</div>
</body>
</html>