<?php

namespace Asimov\Solaria\Modules\Catalog;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;
use Route;

class CatalogModuleServiceProvider extends ServiceProvider{

    public function boot(GateContract $gate){
        $this->registerRoutes();
        $this->registerViews();
        $this->publishMigrationsAndSeeds();
        $this->publishAssets();
        $this->registerPolicies($gate);
    }

    /**
     * Registra una instancia del modulo en la aplicacion
     *
     * @return void
     */
    public function register() {
        $moduleLoader = $this->app->make('solaria.moduleloader');
        $moduleLoader->add(new Catalog());
    }

    /**
     * Registra las rutas del modulo
     */
    private function registerRoutes() {
        Route::group(['middleware' => 'auth', 'namespace' => 'Asimov\Solaria\Modules\Catalog\Http\Controllers'], function() {
            Route::controller('/backend/modules/catalog/products', 'ProductsController');
            Route::controller('/backend/modules/catalog/categories', 'CategoriesController');
            Route::controller('/backend/modules/catalog/attributes', 'AttributesController');
            Route::controller('/backend/modules/catalog/currencies', 'CurrenciesController');
            Route::controller('/backend/modules/catalog/locations', 'LocationsController');
            Route::controller('/backend/modules/catalog/packages', 'PackagesController');
            Route::controller('/backend/modules/catalog/layouts', 'LayoutsController');
            Route::controller('/backend/modules/catalog/taxes', 'TaxesController');
            Route::controller('/backend/modules/catalog', 'CatalogController');
        });
        Route::group(['namespace' => 'Asimov\Solaria\Modules\Catalog\Http\Controllers\Frontend'], function() {
            Route::controller('/modules/catalog/products', 'ProductsController');
        });
    }

    /**
     * Registra las vistas del modulo
     */
    private function registerViews() {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'modulecatalog');
    }

    /**
     * Publica las migraciones del modulo
     */
    private function publishMigrationsAndSeeds() {
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Publica los assets del modulo
     */
    private function publishAssets(){
        $this->publishes([
            __DIR__ . '/public/' => public_path('modules/catalog')
        ], 'assets');
    }

    /**
     * @param GateContract $gate
     */
    private function registerPolicies($gate){
        $gate->define('module_catalog_manage_catalog', function($user){
            return $user->hasRole('admin');
        });
    }
}