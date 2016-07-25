<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatTempQr extends Model
{
    protected $table = 'wechat_temp_qr';
    protected $fillable = [
        'type',
        'params',
    ];

    protected $casts = ['params' => 'json'];
}