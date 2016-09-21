<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Currency;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\User;

class CurrenciesController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        view()->share([
            'currencies' => Currency::where(['site_id' => $this->site->id])->get()
        ]);
        $tabs = [
            'active' => 'currencies',
            'content' => view('modulecatalog::backend.currencies.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $currencyId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function currencyForm($currencyId = null) {
        $currency = $currencyId ? Currency::find($currencyId) : new Currency();
        $currency->site_id = $this->site->id;

        view()->share([
            'currency' => $currency
        ]);

        $tabs = [
            'active' => 'currencies',
            'content' => view('modulecatalog::backend.currencies.form')
        ];

        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        $this->authorize('module_catalog_manage_catalog');

        return $this->currencyForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($currencyId) {
        $this->authorize('module_catalog_manage_catalog');

        return $this->currencyForm($currencyId);
    }

    /**
     * @param $currencyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($currencyId){
        $this->authorize('module_catalog_manage_catalog');

        Currency::destroy($currencyId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado la moneda.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request) {
        $this->authorize('module_catalog_manage_catalog');

        $currency = $request->get('id') ? Currency::find($request->get('id')) : new Currency();

        $currency->site_id = $request->get('site_id');
        $currency->default = $request->get('default');
        $currency->code = $request->get('code');
        $currency->name = $request->get('name');
        $currency->precision = $request->get('precision');
        $currency->symbol = $request->get('symbol');
        $currency->value = $request->get('value');
        $currency->thousands_separator = $request->get('thousands_separator');
        $currency->decimal_point = $request->get('decimal_point');
        $currency->save();

        return response()->json(['errors' => false, 'message' => 'Se ha guardado la moneda.', 'currency' => $currency->toArray()]);
    }
}