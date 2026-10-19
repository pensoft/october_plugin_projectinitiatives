<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesRegionCountry extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_region_country')) {
            Schema::create('pensoft_projectinitiatives_region_country', function (Blueprint $table) {
                $table->integer('region_id')->unsigned();
                $table->integer('country_id')->unsigned();
                $table->primary(['region_id', 'country_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_region_country');
    }
}
