<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogAttributesGroupsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_attributes_groups_attributes', function (Blueprint $table) {
            $table->integer('attribute_group_id')->unsigned();
            $table->integer('attribute_id')->unsigned();

            $table->foreign('attribute_group_id', 'g_to_a_attribute_group_fk')->references('id')->on('module_catalog_attributes_groups')->onDelete('cascade');
            $table->foreign('attribute_id', 'g_to_a_attribute_fk')->references('id')->on('module_catalog_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_attributes_groups_attributes');
    }
}
