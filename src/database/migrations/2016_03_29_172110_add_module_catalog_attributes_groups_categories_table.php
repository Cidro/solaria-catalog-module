<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogAttributesGroupsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_attributes_groups_categories', function (Blueprint $table) {
            $table->integer('attribute_group_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('attribute_group_id', 'g_to_c_attribute_group_fk')->references('id')->on('module_catalog_attributes_groups')->onDelete('cascade');
            $table->foreign('category_id', 'g_to_c_category_fk')->references('id')->on('module_catalog_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_attributes_groups_categories');
    }
}
