<?php

namespace Asimov\Solaria\Modules\Catalog;

use App;
use Asimov\Solaria\Modules\Catalog\Models\Category as CategoryModel;
use Asimov\Solaria\Modules\Catalog\Models\Layout;
use Asimov\Solaria\Modules\Catalog\Models\Product as ProductModel;
use Asimov\Solaria\Modules\Catalog\Models\Package as PackageModel;
use Asimov\Solaria\Modules\Catalog\Models\Location as LocationModel;
use Asimov\Solaria\Modules\Catalog\Models\ProductData;
use Asimov\Solaria\Modules\Catalog\Models\Twig\Category;
use Asimov\Solaria\Modules\Catalog\Models\Twig\Location;
use Asimov\Solaria\Modules\Catalog\Models\Twig\Product;
use Asimov\Solaria\Modules\Catalog\Models\Twig\Package;

class CatalogRenderer {

    /**
     * @var array
     */
    protected $options;

    /**
     * CatalogRenderer constructor.
     * @param array $options
     */
    public function __construct($options = []) {
        $this->options = $options;
    }

    /**
     * @param $categoryId
     * @param null $layoutAlias
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function category($categoryId = null, $layoutAlias = null){
        $category = CategoryModel::where(['site_id' => App::make('site')->id, 'id' => $categoryId])->first();
        if(!$category)
            return null;

        if($layoutAlias != null)
            $layout = Layout::where(['site_id' => App::make('site')->id, 'alias' => $layoutAlias])->first();

        if(!isset($layout) || $layout == null)
            $layout = $category->layout;

        $data['category'] = new Category($category);
        $data['locations'] = $this->locations();

        return view($layout->getLayoutViewFolder(), $data)->render();
    }

    /**
     * @param null $packageId
     * @param null $layoutAlias
     * @return null|string
     * @throws \Exception
     * @throws \Throwable
     */
    public function package($packageId = null, $layoutAlias = null){
        $package = PackageModel::where(['site_id' => App::make('site')->id, 'id' => $packageId])->first();
        if(!$package)
            return null;

        if($layoutAlias != null)
            $layout = Layout::where(['site_id' => App::make('site')->id, 'alias' => $layoutAlias])->first();

        if(!isset($layout) || $layout == null)
            $layout = $package->category->packageLayout;

        $data['category'] = new Category($package->category);
        $data['package'] = new Package($package);
        $data['locations'] = $this->locations();

        return view($layout->getLayoutViewFolder(), $data)->render();
    }

    /**
     * @param null $productId
     * @param null $layoutAlias
     * @return null|string
     * @throws \Exception
     * @throws \Throwable
     */
    public function product($productId = null, $layoutAlias = null){
        if(!is_numeric($productId)){
            $productData = ProductData::where(['language_id' => App::make('site')->getLanguage()->id, 'name' => $productId])->first();
            if(!$productData)
                return null;

            $productId = $productData->id;
        }
        $product = ProductModel::with(['attributes', 'locations'])->where(['site_id' => App::make('site')->id, 'id' => $productId])->first();
        if(!$product)
            return null;

        if($layoutAlias != null)
            $layout = Layout::where(['site_id' => App::make('site')->id, 'alias' => $layoutAlias])->first();

        if(!isset($layout) || $layout == null)
            $layout = $product->category->productLayout;

        $data['category'] = new Category($product->category);
        $data['product'] = new Product($product);
        $data['locations'] = $this->locations();

        return view($layout->getLayoutViewFolder(), $data)->render();
    }

    /**
     * @param array|string $productsIds
     * @return array
     */
    public function products($productsIds){
        $productsIds = gettype($productsIds) != 'array' ? explode(',', $productsIds) : $productsIds;
        $productsModels = ProductModel::with('attributes', 'locations')->whereIn('id', $productsIds)->get();
        $products = collect();

        foreach ($productsModels as $productModel) {
            $products->push(new Product($productModel));
        }

        return $products;
    }

    /**
     * @param array|string $categoriesIds
     * @return array
     */
    public function categories($categoriesIds){
        $categoriesIds = in_array(gettype($categoriesIds),['string', 'integer']) ? explode(',', $categoriesIds) : $categoriesIds;
        $categoriesModels = CategoryModel::whereIn('id', $categoriesIds)->get();
        $categories = collect();

        foreach ($categoriesModels as $categoryModel) {
            $categories->push(new Category($categoryModel));
        }

        return $categories;
    }

    /**
     * @param array|string $packagesIds
     * @return array
     */
    public function packages($packagesIds){
        $packagesIds = gettype($packagesIds) == 'string' ? explode(',', $packagesIds) : $packagesIds;
        $packagesModels = PackageModel::whereIn('id', $packagesIds)->get();
        $packages = collect();

        foreach ($packagesModels as $packageModel) {
            $packages->push(new Package($packageModel));
        }

        return $packages;
    }

    /**
     * @param null $locationsIds
     * @return \Illuminate\Support\Collection
     */
    public function locations($locationsIds = null){
        $locations = collect();
        if($locationsIds){
            $locationsIds = gettype($locationsIds) == 'string' ? explode(',', $locationsIds) : $locationsIds;
            $locationsModels = LocationModel::whereIn('id', $locationsIds)->get();
        } else {
            $locationsModels = LocationModel::with('allChildren')
                ->where(['site_id' => App::make('site')->id])
                ->whereNull('parent_id')
                ->get();
        }

        foreach ($locationsModels as $locationModel) {
            $locations->push(new Location($locationModel));
        }

        return $locations;
    }
}