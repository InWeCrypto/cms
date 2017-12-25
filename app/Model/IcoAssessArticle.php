<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class IcoAssessArticle extends Model
{
	protected $table   = 'ico_assess_articles';
	protected $guarded = [];

    protected $casts = [
        'enable' => 'boolean',
        'is_scroll' => 'boolean',
        'is_top' => 'boolean',
        'is_hot' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enable', function(Builder $builder) {
            return $builder->where('enable','1');
        });

        static::addGlobalScope('sort', function(Builder $builder) {
            return $builder->orderBy('is_top','DESC')
                           ->orderBy('is_hot','DESC')
                           ->orderBy('sort','ASC')
                           ->orderBy('created_at','DESC')
                           ->orderBy('id','DESC');
        });
    }

    public function scopeOfProject($query, $project_id)
    {
        return $query->where('project_id', $project_id);
    }

    public function contents()
    {
        return $this->hasMany('App\Model\IcoAssessArticle','p_id','id');
    }

    public function icoAssessProjectAnalyse()
    {
        return $this->hasMany('App\Model\IcoAssessProjectAnalyse', 'ico_article_id', 'id');
    }

    public function icoAssessStructure()
    {
        return $this->hasMany('App\Model\IcoAssessStructure', 'ico_article_id', 'id');
    }

    public function icoAssessIssueInfo()
    {
        return $this->hasMany('App\Model\IcoAssessIssueInfo', 'ico_article_id', 'id');
    }

    // public function ico()
    // {
    //     return $this->hasOne('App\Model\Ico', 'id', 'ico_id');
    // }

    public function tags()
    {
        return $this->hasMany('App\Model\IcoAssessArticleTag', 'ico_assess_id', 'id');
    }

    public function riskLevelInfo()
    {
        return $this->hasOne('App\Model\IcoAssessLevelTag', 'id', 'risk_level_id');
    }

    public function recommendLevelinfo()
    {
        return $this->hasOne('App\Model\IcoAssessLevelTag', 'id', 'recommend_level_id');
    }




}
