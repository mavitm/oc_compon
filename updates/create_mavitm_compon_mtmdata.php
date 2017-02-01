<?php namespace Mavitm\Compon\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateMavitmComponMtmdata extends Migration
{
    public function up()
    {
        Schema::create('mavitm_compon_mtmdata', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('groups', 250)->nullable();
            $table->string('title', 250)->nullable();
            $table->text('html_description')->nullable();
            $table->text('strdata')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_compon_mtmdata');
    }
}
