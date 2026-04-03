<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesLandscapes extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_landscapes')) {
            Schema::create('pensoft_projectinitiatives_landscapes', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_landscapes');
    }
}
