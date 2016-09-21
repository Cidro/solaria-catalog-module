<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeasingPriceField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_catalog_products', function (Blueprint $table) {
            $table->decimal('leasing_price',14,2)->default(0);
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
            $table->dropColumn('leasing_price');
        });
    }
}
