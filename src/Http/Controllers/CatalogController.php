<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Solaria\Http\Controllers\Backend\BackendController;

class CatalogController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        return redirect('backend/modules/catalog/products/');
    }

}