<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Layout;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;

class LayoutsController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        view()->share([
            'layouts' => Layout::where(['site_id' => $this->site->id])->get()
        ]);
        $tabs = [
            'active' => 'layouts',
            'content' => view('modulecatalog::backend.layouts.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $layoutId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function layoutsForm($layoutId = null) {
        $layout = $layoutId ? Layout::find($layoutId) : new Layout();
        $layout->site_id = $this->site->id;

        view()->share([
            'layout' => $layout
        ]);

        $tabs = [
            'active' => 'layouts',
            'content' => view('modulecatalog::backend.layouts.form')
        ];

        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        $this->authorize('module_catalog_manage_catalog');

        return $this->layoutsForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($layoutId) {
        $this->authorize('module_catalog_manage_catalog');

        return $this->layoutsForm($layoutId);
    }

    /**
     * @param $layoutId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($layoutId){
        $this->authorize('module_catalog_manage_catalog');

        Layout::destroy($layoutId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado la plantilla.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request) {
        $this->authorize('module_catalog_manage_catalog');

        $layout = $request->get('id') ? Layout::find($request->get('id')) : new Layout();

        $layout->site_id = $request->get('site_id');
        $layout->name = $request->get('name');
        $layout->alias = $request->get('alias');
        $layout->html = $request->get('html');
        $layout->save();

        return response()->json(['errors' => false, 'message' => 'Se ha guardado la plantilla.', 'layout' => $layout->toArray()]);
    }
}