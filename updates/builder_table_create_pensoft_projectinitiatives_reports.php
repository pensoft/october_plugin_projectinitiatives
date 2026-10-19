<?php namespace Pensoft\Projectinitiatives\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftProjectinitiativesReports extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pensoft_projectinitiatives_reports')) {
            Schema::create('pensoft_projectinitiatives_reports', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('data_id')->unsigned()->index();
                $table->string('issue_type');
                $table->text('message');
                $table->string('reporter_name')->nullable();
                $table->string('reporter_email')->nullable();
                $table->string('status')->default('open')->index();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pensoft_projectinitiatives_reports');
    }
}
