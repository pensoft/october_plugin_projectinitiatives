<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesDataPivots extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_data_country')) {
            Schema::create('pensoft_projectinitiatives_data_country', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('country_id')->unsigned();
                $table->primary(['data_id', 'country_id']);
            });
        }

        if (!Schema::hasTable('pensoft_projectinitiatives_data_region')) {
            Schema::create('pensoft_projectinitiatives_data_region', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('region_id')->unsigned();
                $table->primary(['data_id', 'region_id']);
            });
        }

        if (!Schema::hasTable('pensoft_projectinitiatives_data_type')) {
            Schema::create('pensoft_projectinitiatives_data_type', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('type_id')->unsigned();
                $table->primary(['data_id', 'type_id']);
            });
        }

        if (!Schema::hasTable('pensoft_projectinitiatives_data_approach')) {
            Schema::create('pensoft_projectinitiatives_data_approach', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('approach_id')->unsigned();
                $table->primary(['data_id', 'approach_id']);
            });
        }

        if (!Schema::hasTable('pensoft_projectinitiatives_data_landscape')) {
            Schema::create('pensoft_projectinitiatives_data_landscape', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('landscape_id')->unsigned();
                $table->primary(['data_id', 'landscape_id']);
            });
        }

        if (!Schema::hasTable('pensoft_projectinitiatives_data_funding')) {
            Schema::create('pensoft_projectinitiatives_data_funding', function (Blueprint $table) {
                $table->integer('data_id')->unsigned();
                $table->integer('funding_id')->unsigned();
                $table->primary(['data_id', 'funding_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_data_country');
        Schema::dropIfExists('pensoft_projectinitiatives_data_region');
        Schema::dropIfExists('pensoft_projectinitiatives_data_type');
        Schema::dropIfExists('pensoft_projectinitiatives_data_approach');
        Schema::dropIfExists('pensoft_projectinitiatives_data_landscape');
        Schema::dropIfExists('pensoft_projectinitiatives_data_funding');
    }
}
