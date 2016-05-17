<?php

namespace App\Model;


use Illuminate\Support\Facades\Schema;

class Article extends Model
{
    protected $table = 'article';


    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\\User','id', 'uid');
    }

    public function comment()
    {
        return $this->hasMany(__NAMESPACE__.'\\Comment','aid', 'id');
    }

    static public function up()
    {
        Schema::create('article', function($table) {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->increments('id')->unsigned();
            $table->integer('uid')->unsigned()->default(0)->comment('作者id');
            $table->string('from',48)->nullable()->comment('来源');
            $table->string('link')->nullable()->comment('链接');
            $table->string('title',96)->nullable()->comment('标题');
            $table->string('cover',96)->nullable()->comment('封面');
            $table->string('author',64)->nullable()->comment('作者');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('times')->nullable()->comment('点击次数');
            $table->timestamp('overdue_at')->nullable()->comment('过期时间');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->boolean('status')->default(0)->comment('0=无效,1=正常');

            $table->index('uid');
            $table->index('status');
            $table->engine = 'InnoDB';
        });
    }

    static public function down()
    {
        Schema::dropIfExists('article');
    }
}