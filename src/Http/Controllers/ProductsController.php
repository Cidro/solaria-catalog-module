<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Attribute;
use Asimov\Solaria\Modules\Catalog\Models\Category;
use Asimov\Solaria\Modules\Catalog\Models\Location;
use Asimov\Solaria\Modules\Catalog\Models\Package;
use Asimov\Solaria\Modules\Catalog\Models\Product;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\Language;
use Solaria\Models\Page;
use Solaria\Models\User;

class ProductsController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        $categories = collect();
        $categoriesModel = Category::where(['site_id' => $this->site->id])
            ->whereNull('parent_id')
            ->get();

        foreach ($categoriesModel as $categoryModel) {
            $categories = $categories->merge($categoryModel->getWithChildrens());
        }

        view()->share([
            'products' => Product::with('category')->where(['site_id' => $this->site->id])->get(),
            'categories' => $categories
        ]);
        $tabs = [
            'active' => 'products',
            'content' => view('modulecatalog::backend.products.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }


    /**
     * @param null $productId
     * @param bool $isCopy
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function productForm($productId = null, $isCopy = false){
        $product = $productId
            ? Product::with([
                    'category',
                    'parent',
                    'package',
                    'children',
                    'locations',
                    'attributes.attribute',
                    'attributes.language'
                ])->find($productId)
            : new Product();
        $product->site_id = $this->site->id;

        $products = Product::where(['site_id' => $this->site->id]);
        if ($productId)
            $products->where('id', '<>', $productId);
        $products = $products->get();

        $locations = Location::with('allChildren')
            ->whereNull('parent_id')
            ->where(['site_id' => $this->site->id])
            ->get();

        view()->share([
            'product' => $product,
            'products' => $products,
            'locations' => $locations,
            'languages' => Language::where('site_id', $this->site->id)->get(),
            'categories' => Category::with('attributesGroups.attributes')->where(['site_id' => $this->site->id])->get(),
            'attributes' => Attribute::where(['site_id' => $this->site->id])->get(),
            'pages' => Page::where(['site_id' => $this->site->id])->get(),
            'packages' => Package::where(['site_id' => $this->site->id])->get(),
            'isCopy' => $isCopy
        ]);
        $tabs = [
            'active' => 'products',
            'content' => view('modulecatalog::backend.products.form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(){
        $this->authorize('module_catalog_manage_catalog');

        return $this->productForm();
    }

    /**
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($productId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->productForm($productId);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCopy($productId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->productForm($productId, true);
    }

    /**
     * @param $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($productId){
        $this->authorize('module_catalog_manage_catalog');

        Product::destroy($productId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado el producto.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $product = $request->get('id') ? Product::find($request->get('id')) : new Product();

        $product->site_id = $request->get('site_id');
        $product->category_id = $request->get('category_id');
        $product->page_id = $request->get('page_id');
        $product->parent_id = $request->get('parent_id');
        $product->package_id = $request->get('package_id');
        $product->price = $request->get('price', 0);
        $product->leasing_price = $request->get('leasing_price', 0);
        $product->code = $request->get('code');
        $product->sku = $request->get('sku');
        $product->images = $request->get('images');
        $product->ordering = $request->get('ordering', null);
        $product->published = $request->get('published', false);
        $product->save();

        $product->setAttributes($request->get('attributes'));
        $product->setData($request->get('data'));
        $product->syncLocations($request->get('locationsIds', []),$request->get('locationsPrices', []));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el producto.', 'product' => $product->toArray()]);
    }
}