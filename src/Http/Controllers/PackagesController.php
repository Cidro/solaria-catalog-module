<?php

namespace Asimov\Solaria\Modules\Catalog\Http\Controllers;

use Asimov\Solaria\Modules\Catalog\Models\Category;
use Asimov\Solaria\Modules\Catalog\Models\Package;
use Asimov\Solaria\Modules\Catalog\Models\Product;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Backend\BackendController;
use Solaria\Models\Language;
use Solaria\Models\User;

class PackagesController extends BackendController {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        $this->authorize('module_catalog_manage_catalog');

        view()->share([
            'packages' => Package::where(['site_id' => $this->site->id])->get()
        ]);
        $tabs = [
            'active' => 'packages',
            'content' => view('modulecatalog::backend.packages.index')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @param null $packageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function packageForm($packageId = null){
        $package = $packageId ? Package::with('products', 'childPackages')->find($packageId) : new Package();
        $package->site_id = $this->site->id;
        $products = Product::with('category')->where (['site_id' => $this->site->id])->get();
        $categories = Category::where(['site_id' => $this->site->id])->get();
        $packages = Package::where(['site_id' => $this->site->id]);

        if($packageId)
            $packages->where('id', '<>', $packageId);

        $packages = $packages->get();

        view()->share([
            'package' => $package,
            'products' => $products,
            'categories' => $categories,
            'packages' => $packages,
            'languages' => Language::where('site_id', $this->site->id)->get()
        ]);
        $tabs = [
            'active' => 'packages',
            'content' => view('modulecatalog::backend.packages.form')
        ];
        $data['content'] = view('modulecatalog::backend.index', $tabs);
        return view($this->layout, $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(){
        $this->authorize('module_catalog_manage_catalog');

        return $this->packageForm();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($packageId){
        $this->authorize('module_catalog_manage_catalog');

        return $this->packageForm($packageId);
    }

    /**
     * @param $packageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($packageId){
        $this->authorize('module_catalog_manage_catalog');

        Package::destroy($packageId);

        return response()->json(['errors' => false, 'message' => 'Se ha eliminado el paquete de productos.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSave(Request $request){
        $this->authorize('module_catalog_manage_catalog');

        $package = $request->get('id') ? Package::find($request->get('id')) : new Package();

        $package->site_id = $request->get('site_id');
        $package->category_id = $request->get('category_id');
        $package->images = $request->get('images');
        $package->price = $request->get('price');
        $package->code = $request->get('code');
        $package->published = $request->get('published', false);
        $package->ordering = $request->get('ordering', null);
        $package->save();
        $package->products()->sync($request->get('productsIds'));
        $package->childPackages()->sync($request->get('childPackagesIds'));
        $package->setData($request->get('data'));

        return response()->json(['errors' => false, 'message' => 'Se ha guardado el paquete de productos.', 'package' => $package->toArray()]);
    }
}