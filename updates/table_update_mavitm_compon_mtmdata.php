<?php namespace Mavitm\Compon\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableUpdateMavitmComponMtmdata extends Migration
{
    public function up()
    {
        Schema::table('mavitm_compon_mtmdata', function($table)
        {
            $table->renameColumn('group', 'groups');
            $table->mediumText('html_description')->nullable()->unsigned(false)->default(null)->change();
            $table->mediumText('strdata')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('mavitm_compon_mtmdata', function($table)
        {
            $table->renameColumn('groups', 'group');
            $table->text('html_description')->nullable()->unsigned(false)->default(null)->change();
            $table->text('strdata')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
