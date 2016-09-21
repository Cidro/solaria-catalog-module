<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('page_id')->nullable()->unsigned();
            $table->integer('layout_id')->nullable()->unsigned();
            $table->integer('product_layout_id')->nullable()->unsigned();
            $table->integer('package_layout_id')->nullable()->unsigned();
            $table->boolean('is_visible')->default(false)->nullable();
            $table->text('images');
            $table->timestamps();
        });
        Schema::table('module_catalog_categories', function(Blueprint $table){
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('module_catalog_categories')->onDelete('set null');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
            $table->foreign('layout_id')->references('id')->on('layouts')->onDelete('set null');
            $table->foreign('product_layout_id')->references('id')->on('layouts')->onDelete('set null');
            $table->foreign('package_layout_id')->references('id')->on('layouts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_categories');
    }
}
