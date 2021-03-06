<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeNoticesTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_notices_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source')->comment('文章来源');
            $table->string('uri', 512)->comment('数据来源页');
            $table->string('uri_md5')->comment('数据来源页md5值');
            $table->dateTime('article_date')->comment('文章日期');
            $table->string('article_title')->nullable()->comment('文章标题');
            $table->text('article_content')->nullable()->comment('文章内容');
            $table->integer('article_id')->default(0)->comment('发布文章ID');
            $table->string('lang')->default('zh')->comment('文章语言');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_notices_temp');
    }
}
