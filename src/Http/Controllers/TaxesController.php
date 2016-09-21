<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Tax;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;

class TaxesController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        view()->share([
            'taxes' => Tax::where(['site_id' => $this->site->id])->get()
        ]);
        $tabs = [
            'active' => 'taxes',
            'content' => view('modulecatalog::backend.taxes.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $taxId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function taxForm($taxId = null) {
        $tax = $taxId ? Tax::find($taxId) : new Tax();
        $tax->site_id = $this->site->id;

        view()->share([
            'tax' => $tax
        ]);

        $tabs = [
            'active' => 'taxes',
            'content' => view('modulecatalog::backend.taxes.form')
        ];

        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        $this->authorize('module_catalog_manage_catalog');

        return $this->taxForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($taxId) {
        $this->authorize('module_catalog_manage_catalog');

        return $this->taxForm($taxId);
    }

    /**
     * @param $taxId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($taxId){
        $this->authorize('module_catalog_manage_catalog');

        Tax::destroy($taxId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado el impuesto.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request) {
        $this->authorize('module_catalog_manage_catalog');

        $tax = $request->get('id') ? Tax::find($request->get('id')) : new Tax();

        $tax->site_id = $request->get('site_id');
        $tax->default = $request->get('default');
        $tax->name = $request->get('name');
        $tax->value = $request->get('value');
        $tax->save();

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el impuesto.', 'tax' => $tax->toArray()]);
    }
}