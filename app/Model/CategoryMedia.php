<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryMedia extends Model
{
	protected $table = 'category_medias';

    protected $fillable = [
        'name',
        'qr_img',
        'url',
        'img',
        'sort',
        'enable',
        'category_id'
    ];
}
