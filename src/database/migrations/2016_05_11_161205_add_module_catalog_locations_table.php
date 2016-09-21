<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('code');
            $table->timestamps();
        });
        Schema::table('module_catalog_locations', function(Blueprint $table){
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('module_catalog_locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_locations');
    }
}
