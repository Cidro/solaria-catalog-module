<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleCatalogCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_catalog_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->boolean('default')->default(false);
            $table->string('code');
            $table->string('name');
            $table->integer('precision')->unsigned();
            $table->string('symbol', 16);
            $table->char('thousands_separator', 1)->default(',');
            $table->char('decimal_point', 1)->default('.');
            $table->double('value');
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_catalog_currencies');
    }
}
