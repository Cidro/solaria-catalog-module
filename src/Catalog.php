<?php
namespace Asimov\Solaria\Modules\Catalog;

use Solaria\Modules\SolariaModule;

class Catalog implements SolariaModule {

    protected $name = 'Catalog';

    protected $menu_name = 'Catálogo';

    public function getName() {
        return $this->name;
    }

    public function getMenuName() {
        return $this->menu_name;
    }

    public function getBackendMenuUrl() {
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

    public function render(){
        return 'catalog';
    }
}