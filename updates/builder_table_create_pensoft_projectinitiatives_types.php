<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesTypes extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_types')) {
            Schema::create('pensoft_projectinitiatives_types', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('title');
                $table->text('tags')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_types');
    }
}