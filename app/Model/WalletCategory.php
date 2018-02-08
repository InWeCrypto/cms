<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WalletCategory
 * @package App\Model
 */
class WalletCategory extends Model
{
    protected $table = 'wallet_categorys';
	/**
	 * @var array
	 */
	protected $fillable = [
		"name",
	];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

	public function icoInfo()
	{
		return $this->hasOne(IcoList::class, 'symbol', 'name')->select('symbol','name');
	}
}
