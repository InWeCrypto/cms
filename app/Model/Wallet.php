<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Wallet
 * @package App\Model
 */
class Wallet extends Model
{

	use SoftDeletes;

    protected $table = 'wallets';

	/**
	 * @var array
	 */
	protected $fillable = [
		"user_id",
		"category_id",
		"name",
		"address",
        "address_hash160"
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo(WalletCategory::class, 'category_id')->select('id','name');
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function gnt()
	{
		return $this->hasMany(UserGntCategory::class,'wallet_id');
	}
	/**
	 * 根据地址查询
	 *
	 * @param $query
	 * @param $address
	 * @return mixed
	 */
	public function scopeOfAddress($query, $address)
	{
		return $address ? $query->where("address", $address) : $query;
	}


	/**
	 * 根据用户ID查询
	 *
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
	 * @param $keyword
	 * @return mixed
	 */
	public function scopeOfKeyword($query, $keyword)
	{
		return $keyword ? $query->where(function ($query) use($keyword){
			$query->where('address', 'like', "%$keyword%")->orWhere('name', 'like', "%$keyword%")->orWhereHas('user',function ($query) use ($keyword){
				$query->where('nickname', 'like', "%$keyword%")->orWhere('open_id', 'like', "%$keyword%");
			});
		}) : $query;
	}
}
