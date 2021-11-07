<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function rand(int $len = 8): string
    {
        $code = '';
        $chars = '1234567890';
        for ($i = 0; $i < $len; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }
}
