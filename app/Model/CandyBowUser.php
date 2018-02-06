<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CandyBowUser extends Model
{
	protected $table = 'candy_bow_user_relations';

    protected $fillable = [
        'user_id',
        'bow_date',
        'lang'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }
}
