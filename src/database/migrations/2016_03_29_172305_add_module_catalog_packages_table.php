<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('category_id')->nullable()->unsigned();
            $table->decimal('price',14,2)->default(0);
            $table->text('images');
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('module_catalog_categories')->onDelete('set null');
        });

        //Se crea la relación con la tabla de productos
        Schema::table('module_catalog_products', function(Blueprint $table){
            $table->foreign('package_id')->references('id')->on('module_catalog_packages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_catalog_products', function(Blueprint $table){
            $table->dropForeign('module_catalog_products_package_id_foreign');
        });
        Schema::drop('module_catalog_packages');
    }
}
