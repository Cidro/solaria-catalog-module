<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Attribute;
use Asimov\Solaria\Modules\Catalog\Models\AttributeGroup;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\Language;
use Solaria\Models\User;

class AttributesController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        view()->share([
            'attributes' => Attribute::with('groups')->where(['site_id' => $this->site->id])->get(),
            'attributesGroups' => AttributeGroup::where(['site_id' => $this->site->id])->get()
        ]);
        $tabs = [
            'active' => 'attributes',
            'content' => view('modulecatalog::backend.attributes.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $attributeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function attributeForm($attributeId = null){
        $attribute = $attributeId ? Attribute::with('groups')->find($attributeId) : new Attribute();
        $attribute->site_id = $this->site->id;

        view()->share([
            'attribute' => $attribute,
            'attributesGroups' => AttributeGroup::where(['site_id' => $this->site->id])->get(),
            'languages' => Language::where('site_id', $this->site->id)->get()
        ]);
        $tabs = [
            'active' => 'attributes',
            'content' => view('modulecatalog::backend.attributes.form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(){
        $this->authorize('module_catalog_manage_catalog');

        return $this->attributeForm();
    }

    /**
     * @param $attributeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($attributeId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->attributeForm($attributeId);
    }

    /**
     * @param $attributeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($attributeId){
        $this->authorize('module_catalog_manage_catalog');

        Attribute::destroy($attributeId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado el atributo.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $attribute = $request->get('id') ? Attribute::find($request->get('id')) : new Attribute();

        $attribute->site_id = $request->get('site_id');
        $attribute->save();
        $attribute->groups()->sync($request->get('attributesGroupsIds'));
        $attribute->setData($request->get('data'));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el atributo.', 'attribute' => $attribute->toArray()]);
    }

    /**
     * @param null $attributeGroupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function attributeGroupForm($attributeGroupId = null){
        $attributeGroup = $attributeGroupId ? AttributeGroup::with('attributes')->find($attributeGroupId) : new AttributeGroup();
        $attributeGroup->site_id = $this->site->id;
        $attributes = Attribute::where(['site_id' => $this->site->id])->get();

        view()->share([
            'attributeGroup' => $attributeGroup,
            'attributes' => $attributes,
            'languages' => Language::where('site_id', $this->site->id)->get()
        ]);

        $tabs = [
            'active' => 'attributes',
            'content' => view('modulecatalog::backend.attributes.group-form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateGroup(){
        $this->authorize('module_catalog_manage_catalog');

        return $this->attributeGroupForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditGroup($attributeGroupId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->attributeGroupForm($attributeGroupId);
    }

    /**
     * @param $attributeGroupId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteGroup($attributeGroupId){
        $this->authorize('module_catalog_manage_catalog');

        AttributeGroup::destroy($attributeGroupId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado el grupo de atributos.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSaveGroup(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $attributeGroup = $request->get('id') ? AttributeGroup::find($request->get('id')) : new AttributeGroup();

        $attributeGroup->site_id = $request->get('site_id');
        $attributeGroup->save();
        $attributeGroup->attributes()->sync($request->get('attributesIds'));
        $attributeGroup->setData($request->get('data'));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el grupo de atributo.', 'attributeGroup' => $attributeGroup->toArray()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postQuickAddAttribute(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $attribute = new Attribute();

        $attribute->site_id = $request->get('site_id');
        $attribute->save();
        $attribute->setData($request->get('data'));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el atributo.', 'attribute' => $attribute->toArray()]);
    }
}