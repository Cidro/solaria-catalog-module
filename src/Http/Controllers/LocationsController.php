<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Location;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\Language;

class LocationsController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        $locations = Location::allWithChildren($this->site->id);

        view()->share([
            'locations' => $locations
        ]);
        $tabs = [
            'active' => 'locations',
            'content' => view('modulecatalog::backend.locations.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $locationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function locationForm($locationId = null){
        $location = $locationId ? Location::with('children')->find($locationId) : new Location();
        $location->site_id = $this->site->id;

        $locations = Location::where(['site_id' => $this->site->id]);
        if($locationId)
            $locations->where('id', '<>', $locationId);

        $locations = $locations->get();

        view()->share([
            'location' => $location,
            'locations' => $locations,
            'languages' => Language::where('site_id', $this->site->id)->get()
        ]);
        $tabs = [
            'active' => 'locations',
            'content' => view('modulecatalog::backend.locations.form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(){
        $this->authorize('module_catalog_manage_catalog');

        return $this->locationForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($locationId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->locationForm($locationId);
    }

    /**
     * @param $locationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($locationId){
        $this->authorize('module_catalog_manage_catalog');

        Location::destroy($locationId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado la localidad.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $location = $request->get('id') ? Location::find($request->get('id')) : new Location();
        $childrenIds = $request->get('childrenIds', []);
        $parentId = $request->get('parent_id', null);

        if($location->checkParentAndChildren($parentId, $childrenIds)){
            $location->site_id = $request->get('site_id');
            $location->code = $request->get('code');
            $location->parent_id = $request->get('parent_id', null);
            $location->save();
            $location->setData($request->get('data'));

            if(count($childrenIds))
                Location::whereIn('id', $childrenIds)->update(['parent_id' => $location->id]);

            return response()->json(['errors' => false, 'message' => 'Se ha guardado la localidad.', 'location' => $location->toArray()]);
        } else {
            return response()->json(['errors' => true, 'message' => 'La localidad padre no puede pertenecer a las Sub Localidades.'], 500);
        }
    }
}