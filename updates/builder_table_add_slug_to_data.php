<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableAddSlugToData extends Migration
{
    public function up()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
        });

        // Generate slugs for existing records
        $records = \Db::table('pensoft_projectinitiatives_data')->get();
        foreach ($records as $record) {
            $slug = str_slug($record->title);
            \Db::table('pensoft_projectinitiatives_data')
                ->where('id', $record->id)
                ->update(['slug' => $slug]);
        }
    }

    public function down()
    {
        Schema::table('pensoft_projectinitiatives_data', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
