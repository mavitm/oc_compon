<?php namespace Mavitm\Compon\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddParentTypeFieldToTable extends Migration
{
    public function up()
    {
        Schema::table('mavitm_compon_mtmdata', function($table)
        {
            $table->string('parent_type', 200)->nullable()->index();
        });
    }

    public function down()
    {
        Schema::table('mavitm_compon_mtmdata', function($table)
        {
            $table->dropColumn('parent_type');
        });
    }
}
