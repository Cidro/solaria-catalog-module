<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogPackagesPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_packages_packages', function (Blueprint $table) {
            $table->integer('parent_package_id')->unsigned();
            $table->integer('child_package_id')->unsigned();

            $table->foreign('parent_package_id')->references('id')->on('module_catalog_packages')->onDelete('cascade');
            $table->foreign('child_package_id')->references('id')->on('module_catalog_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_packages_packages');
    }
}
