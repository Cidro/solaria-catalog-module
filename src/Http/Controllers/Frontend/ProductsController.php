<?php
namespace Asimov\Solaria\Modules\Catalog\Http\Controllers\Frontend;


use Asimov\Solaria\Modules\Catalog\Models\Currency;
use Asimov\Solaria\Modules\Catalog\Models\Layout;
use Asimov\Solaria\Modules\Catalog\Models\Product as ProductModel;
use Asimov\Solaria\Modules\Catalog\Models\Tax;
use Asimov\Solaria\Modules\Catalog\Models\Twig\Product;
use Cache;
use Illuminate\Http\Request;
use Solaria\Http\Controllers\Frontend\FrontendController;

class ProductsController extends FrontendController {

    public function getView(Request $request, $productId) {
        $product = ProductModel::find($productId);
        $category = $product->category;
        $layoutAlias = $request->get('layout-alias', null);

        if ($layoutAlias != null)
            $layout = Layout::where(['site_id' => $product->site_id, 'alias' => $layoutAlias])->first();

        if (!isset($layout) || $layout == null)
            $layout = $category->productLayout;

        $data = [
            'product' => new Product($product)
        ];

        return view($layout->getLayoutViewFolder(), $data)->render();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getProductData(Request $request){
        if (env('CACHE_ENABLED', false)) {
            $cacheKey = md5('catalog-product-data-' . $this->site->id . '-' . serialize($request->all()));
            if (Cache::has($cacheKey))
                return Cache::get($cacheKey);
        }

        $product = ProductModel::with([
            'category',
            'attributes.attribute',
            'package.products.category.data.language',
            'package.products.data.language',
            'package.products.attributes.attribute.data.language',
            'package.products.attributes.language',
        ])->find($request->get('productId'));

        $currency = Currency::where([
            'site_id' => $this->site->id,
            'default' => true
        ])->first();

        $tax = Tax::where([
            'site_id' => $this->site->id,
            'default' => true
        ])->first();

        $response = response()->json([
            'product' => $product->toArray(),
            'tax' => $tax->toArray(),
            'currency' => $currency->toArray()
        ]);

        if (env('CACHE_ENABLED', false) && isset($cacheKey))
            Cache::add($cacheKey, $response, env('CACHE_TTL', 1440));

        return $response;
    }
}