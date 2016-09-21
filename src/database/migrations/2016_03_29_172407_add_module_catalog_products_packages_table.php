<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogProductsPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_products_packages', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('package_id')->unsigned()->nullable();

            $table->foreign('product_id')->references('id')->on('module_catalog_products')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('module_catalog_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_products_packages');
    }
}
