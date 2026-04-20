<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableAddConfirmationToData extends Migration
{
    public function up()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->string('confirmation_token')->nullable();
            $table->boolean('is_confirmed')->default(false);
        });
    }

    public function down()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->dropColumn('confirmation_token');
            $table->dropColumn('is_confirmed');
        });
    }
}
