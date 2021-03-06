<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('category_id')->nullable()->unsigned();
            $table->integer('page_id')->nullable()->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('package_id')->nullable()->unsigned();
            $table->string('code');
            $table->string('sku');
            $table->decimal('price',14,2)->default(0);
            $table->text('images');
            $table->timestamps();
        });
        Schema::table('module_catalog_products', function(Blueprint $table){
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('module_catalog_products')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('module_catalog_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_products');
    }
}
