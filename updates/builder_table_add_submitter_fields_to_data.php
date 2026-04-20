<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableAddSubmitterFieldsToData extends Migration
{
    public function up()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->string('submitter_name')->nullable();
            $table->string('submitter_email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->dropColumn('submitter_name');
            $table->dropColumn('submitter_email');
        });
    }
}
