<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableAddIsActiveToData extends Migration
{
    public function up()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
