<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Feedback extends Model
{
	protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'content',
        'contact',
    ];

    protected $appends = [
        'type_name'
    ];

    public static $type = [
        1 => '功能建议',
        2 => '内容建议',
        3 => '其他'
    ];

    public function user()
    {
        return $this->hasone(\App\User::class, 'id', 'user_id');
    }

    public function getTypeNameAttribute()
    {
        return self::$type[$this->type];
    }
}
