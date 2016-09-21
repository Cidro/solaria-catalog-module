<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogAttributeGroupDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_attribute_group_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_group_id')->unsigned();
            $table->integer('language_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('description');
            $table->timestamps();

            $table->foreign('attribute_group_id')->references('id')->on('module_catalog_attributes_groups')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_attribute_group_data');
    }
}
