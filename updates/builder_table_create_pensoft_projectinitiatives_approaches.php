<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesApproaches extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_approaches')) {
            Schema::create('pensoft_projectinitiatives_approaches', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('title');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_approaches');
    }
}