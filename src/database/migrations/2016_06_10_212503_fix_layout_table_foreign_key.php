<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixLayoutTableForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_catalog_categories', function (Blueprint $table) {
            $table->dropForeign('module_catalog_categories_layout_id_foreign');
            $table->foreign('layout_id', 'module_catalog_categories_layout_id_foreign')->references('id')->on('module_catalog_layouts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_catalog_categories', function (Blueprint $table) {
            $table->dropForeign('module_catalog_categories_layout_id_foreign');
            $table->foreign('layout_id')->references('id')->on('layouts')->onDelete('set null');
        });
    }
}
