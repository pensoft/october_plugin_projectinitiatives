<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableSetConfirmedDefaultTrue extends Migration
{
    public function up()
    {
        // Set all existing records to confirmed
        \Db::table('pensoft_projectinitiatives_data')
            ->update(['is_confirmed' => true]);

        // Change default to true for new records created via backend
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(true)->change();
        });
    }

    public function down()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->change();
        });
    }
}
