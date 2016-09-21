<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderingFieldToProductsAndPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_catalog_products', function (Blueprint $table) {
            $table->integer('ordering')->after('images')->nullable();
            $table->boolean('published')->after('images')->default(true);
        });
        Schema::table('module_catalog_packages', function (Blueprint $table) {
            $table->integer('ordering')->after('images')->nullable();
            $table->boolean('published')->after('images')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_catalog_products', function (Blueprint $table) {
            $table->dropColumn('ordering');
            $table->dropColumn('published');
        });
        Schema::table('module_catalog_packages', function (Blueprint $table) {
            $table->dropColumn('ordering');
            $table->dropColumn('published');
        });
    }
}
