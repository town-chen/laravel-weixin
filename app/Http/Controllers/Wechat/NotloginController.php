<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use App\Models\Course;

class NotloginController extends Controller
{
    public function getIndex()
    {
        return view('notlogin.index');
    }

    public function getCourse()
    {
        $courses = Course::take(2)->get();
        $wechat = app('wechat');


        $js = $wechat->js;
        // 页面里的wx.config

        return view('notlogin.course', compact('courses', 'js'));
    }
}