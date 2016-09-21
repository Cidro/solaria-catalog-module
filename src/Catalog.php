<?php
namespace Asimov\Solaria\Modules\Catalog;

use App;
use Asimov\Solaria\Modules\Catalog\Models\Currency;
use Asimov\Solaria\Modules\Catalog\Models\Tax;
use Auth;
use Solaria\Modules\SolariaModule;

class Catalog implements SolariaModule {

    protected $name = 'Catalog';

    protected $menu_name = 'Catálogo';

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var Tax
     */
    protected $tax;

    public function getCurrentCurrency(){
        if(!$this->currency){
            $currencyId = session('module.catalog.currencyId', null);
            $this->currency = $currencyId
                ? Currency::find($currencyId)
                : Currency::where(['site_id' => App::make('site')->id, 'default' => true])->first();
        }
        return $this->currency;
    }

    public function getCurrentTax(){
        if(!$this->tax){
            $taxId = session('module.catalog.taxId', null);
            $this->tax = $taxId
                ? Tax::find($taxId)
                : Tax::where(['site_id' => App::make('site')->id, 'default' => true])->first();
        }
        return $this->tax;
    }


    public function getName() {
        return $this->name;
    }

    public function getMenuName() {
        return $this->menu_name;
    }

    public function getBackendMenuUrl() {
        if(Auth::user()->can('module_catalog_manage_catalog'))
            return url('backend/modules/catalog');
    }

    public function getBackendStyles() {
        return [asset('modules/catalog/css/catalog-module.css')];
    }

    public function getFrontendStyles() {
        // TODO: Implement getFrontendStyles() method.
    }

    public function getBackendScripts() {
        return [asset('modules/catalog/js/catalog-module.js')];
    }

    public function getFrontendScripts() {
        // TODO: Implement getFrontendScripts() method.
    }

    public function getCustomFields() {
        // TODO: Implement getCustomFields() method.
    }

    /**
     * @param null $options
     * @return CatalogRenderer
     */
    public function render($options = null){
        $renderer = new CatalogRenderer($options);
        return $renderer;
    }
}