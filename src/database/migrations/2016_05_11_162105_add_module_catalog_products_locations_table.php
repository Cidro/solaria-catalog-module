<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogProductsLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_products_locations', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('location_id')->unsigned()->nullable();
            $table->decimal('price',14,2)->default(0);
            $table->decimal('leasing_price',14,2)->default(0);

            $table->foreign('product_id')->references('id')->on('module_catalog_products')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('module_catalog_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_products_locations');
    }
}
