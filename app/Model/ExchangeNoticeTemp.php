<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ExchangeNoticeTemp extends Model
{
	protected $table = 'exchange_notices_temp';

    protected $fillable = [
        'uri',
        'uri_md5',
        'source',
        'lang',
        'article_title',
        'article_content',
        'article_date',
        'article_id'
    ];
}
