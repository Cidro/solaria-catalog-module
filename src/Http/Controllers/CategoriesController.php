<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\AttributeGroup;
use Asimov\Solaria\Modules\Catalog\Models\Category;
use Asimov\Solaria\Modules\Catalog\Models\Layout;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\Language;
use Solaria\Models\Page;

class CategoriesController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex() {
        $this->authorize('module_catalog_manage_catalog');

        $categories = collect();
        $categoriesModel = Category::where(['site_id' => $this->site->id])
            ->whereNull('parent_id')
            ->get();

        foreach ($categoriesModel as $categoryModel) {
            $categories = $categories->merge($categoryModel->getWithChildrens());
        }

        view()->share([
            'categories' => $categories
        ]);
        $tabs = [
            'active' => 'categories',
            'content' => view('modulecatalog::backend.categories.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $categoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function categoryForm($categoryId = null, $isCopy = false) {
        $category = $categoryId ? Category::with('attributesGroups')->find($categoryId) : new Category();
        $category->site_id = $this->site->id;

        $categories = Category::where(['site_id' => $this->site->id]);
        if ($categoryId)
            $categories->where('id', '<>', $categoryId);
        $categories = $categories->get();

        view()->share([
            'categories' => $categories,
            'category' => $category,
            'layouts' => Layout::where(['site_id' => $this->site->id])->get(),
            'pages' => Page::where(['site_id' => $this->site->id])->get(),
            'attributesGroups' => AttributeGroup::where('site_id', $this->site->id)->get(),
            'languages' => Language::where('site_id', $this->site->id)->get(),
            'isCopy' => $isCopy
        ]);
        $tabs = [
            'active' => 'categories',
            'content' => view('modulecatalog::backend.categories.form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        $this->authorize('module_catalog_manage_catalog');

        return $this->categoryForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($categoryId) {
        $this->authorize('module_catalog_manage_catalog');

        return $this->categoryForm($categoryId);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCopy($categoryId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->categoryForm($categoryId, true);
    }

    /**
     * @param $categoryId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($categoryId) {
        $this->authorize('module_catalog_manage_catalog');

        Category::destroy($categoryId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado la categoría.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request) {
        $this->authorize('module_catalog_manage_catalog');

        $category = $request->get('id') ? Category::find($request->get('id')) : new Category();

        $category->site_id = $request->get('site_id');
        $category->layout_id = $request->get('layout_id');
        $category->page_id = $request->get('page_id');
        $category->parent_id = $request->get('parent_id');
        $category->product_layout_id = $request->get('product_layout_id');
        $category->package_layout_id = $request->get('package_layout_id');
        $category->is_visible = $request->get('is_visible');
        $category->images = $request->get('images');
        $category->save();
        $category->attributesGroups()->sync($request->get('attributesGroupsIds'));
        $category->setData($request->get('data'));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado la categoría.', 'category' => $category->toArray()]);
    }
}