<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserGntCategory
 * @package App\Model
 */
class UserGntCategory extends Model
{
    protected $table = 'user_gnt_categorys';
	/**
	 * @var array
	 */
	protected $fillable = [
		"user_id",
		"gnt_category_id",
		"name",
		"wallet_id",//钱包ID
		"updated_at",
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function gntCategory()
	{
		return $this->belongsTo(GntCategory::class, 'gnt_category_id');
	}


	/**
	 * @param $query
	 * @param $userId
	 * @return mixed
	 */
	public function scopeOfUserId($query, $userId)
	{
		return $userId ? $query->where('user_id', $userId) : $query;
	}

	/**
	 * @param $query
	 * @param $wallet
	 * @return mixed
	 */
	public function scopeOfWalletId($query, $wallet)
	{
		return $wallet ? $query->where('wallet_id', $wallet) : $query;
	}
}
