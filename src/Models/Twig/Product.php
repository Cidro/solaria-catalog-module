<?php
namespace Asimov\Solaria\Modules\Catalog\Models\Twig;

use App;
use Asimov\Solaria\Modules\Catalog\Models\Category as CategoryModel;
use Asimov\Solaria\Modules\Catalog\Models\Currency;
use Asimov\Solaria\Modules\Catalog\Models\Product as ProductModel;
use Asimov\Solaria\Modules\Catalog\Models\Tax;

class Product extends BaseTwig {

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $attributesGroups = [];

    /**
     * @var ProductModel
     */
    protected $productModel;

    /**
     * @var CategoryModel
     */
    protected $categoryModel;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var Tax
     */
    protected $tax;

    /**
     * Category constructor.
     * @param ProductModel $product
     */
    public function __construct($product) {
        parent::__construct();
        $this->productModel = $product;
        $this->modelData = $product->toArray();
        $this->categoryModel = $product->category;

        $allAttributes = $this->productModel->attributes()->with('attribute.groups')->where([
            'language_id' => App::make('site')->getLanguage()->id
        ])->get()->toArray();

        $this->allAttributes = $this->prepareAttributes($allAttributes, false);
        $this->attributes = $this->prepareAttributes($allAttributes);
        $this->attributesGroups = $this->prepareAttributesGroups($this->categoryModel->attributesGroups->toArray());
        $this->currency = App::make('solaria.moduleloader')->getModule('catalog')->getCurrentCurrency();
        $this->tax = App::make('solaria.moduleloader')->getModule('catalog')->getCurrentTax();
        $this->prepareImages();
    }

    /**
     * @param $attribute
     * @return array
     */
    protected function prepareAttribute($attribute){
        return [
            'name' => array_get($attribute, 'attribute.name'),
            'description' => array_get($attribute, 'attribute.description'),
            'value' => array_get($attribute, 'value'),
            'groups' => array_column(array_get($attribute, 'attribute.groups', []), 'id'),
            'alias' => str_slug(array_get($attribute, 'attribute.name'))
        ];
    }

    /**
     * Prepara los attributos
     * @param $attributes
     * @param bool $assoc
     * @return array
     */
    protected function prepareAttributes($attributes, $assoc = true){
        $preparedAttributes = [];

        foreach ($attributes as $attribute) {
            if($assoc)
                $preparedAttributes[str_slug(array_get($attribute, 'attribute.name'))] = $this->prepareAttribute($attribute);
            else
                $preparedAttributes[] = $this->prepareAttribute($attribute);
        }
        return $preparedAttributes;
    }

    /**
     * @param $attributeGroups
     * @return array
     */
    protected function prepareAttributesGroups($attributeGroups){
        $preparedAttributesGroups = [];
        foreach ($attributeGroups as $attributeGroup) {
            $preparedAttributesGroups[str_slug(array_get($attributeGroup, 'name'))] = [
                'name' => array_get($attributeGroup, 'name'),
                'alias' => str_slug(array_get($attributeGroup, 'name')),
                'description' => array_get($attributeGroup, 'description'),
                'attributes' => $this->getAttributesInGroup($attributeGroup['id'])
            ];
        }
        return $preparedAttributesGroups;
    }

    /**
     * @param $groupId
     * @return array
     */
    protected function getAttributesInGroup($groupId){
        $attributesInGroup = [];
        foreach ($this->allAttributes as $attribute) {
            if(in_array($groupId, $attribute['groups']))
                $attributesInGroup[$attribute['alias']] = $attribute;
        }
        return $attributesInGroup;
    }

    /**
     * @return string
     */
    protected function getUrl(){
        return $this->productModel->page ? $this->productModel->page->getUrl() : '#';
    }

    /**
     * @param bool $formatted
     * @param bool $leasing
     * @return mixed
     */
    protected function getPrice($formatted = true, $leasing = false){
        $price = $leasing ? $this->modelData['leasing_price'] : $this->modelData['price'];
        return $formatted ? $this->currency->format($price) : $price;
    }

    /**
     * @param bool $formatted
     * @param bool $leasing
     * @return mixed
     */
    protected function getTaxablePrice($formatted = true, $leasing = false){
        $price = ($leasing ? $this->modelData['leasing_price'] : $this->modelData['price']) + $this->getTax();
        return $formatted ? $this->currency->format($price) : $price;
    }

    /**
     * @param bool $formatted
     * @param bool $leasing
     * @return mixed|null|string
     */
    protected function getLocationPrice($formatted = true, $leasing = false){
        $price = null;
        if($this->locationFilter){
            $locationPrices = [];
            $this->productModel->locations->map(function($value, $key) use (&$locationPrices, $leasing) {
                if(key_exists('sub-location', $this->locationFilter) && $this->locationFilter['sub-location'] == $value->id)
                    $locationPrices['sub-location'] = $leasing ? $value->pivot->leasing_price : $value->pivot->price;
                if(key_exists('location', $this->locationFilter) && $this->locationFilter['location'] == $value->id)
                    $locationPrices['location'] = $leasing ? $value->pivot->leasing_price : $value->pivot->price;
            });
            if(key_exists('location', $locationPrices) && $locationPrices['location'] > 0)
                $price = $locationPrices['location'];
            if(key_exists('sub-location', $locationPrices) && $locationPrices['sub-location'] > 0)
                $price = $locationPrices['sub-location'];
        }

        $price = $price ? $price : ($leasing ? $this->modelData['leasing_price'] : $this->modelData['price']);

        return $formatted ? $this->currency->format($price) : $price;
    }

    /**
     * @param bool $leasing
     * @return string
     */
    protected function getTaxableLocationPrice($leasing = false){
        return $this->currency->format($this->getLocationPrice(false, $leasing) + $this->getTax());
    }

    /**
     * @return mixed
     */
    private function getTax() {
        return $this->tax->get($this->modelData['price']);
    }

    /**
     * @param $arguments
     * @return array
     */
    protected function toArray($arguments){
        $options = array_get($arguments, '0', []);
        $array = [
            'id' => array_get($this->modelData, 'id'),
            'name' => array_get($this->modelData, 'name'),
            'description' => array_get($this->modelData, 'description'),
            'alias' => array_get($this->modelData, 'alias'),
            'images' => $this->images,
            'image' => $this->image,
            'translations' => array_get($this->modelData, 'data.translations'),
            'category_id' => array_get($this->modelData, 'category_id'),
            'url' => $this->getUrl(),
            'raw_price' => $this->getPrice(false),
            'raw_leasing_price' => $this->getPrice(false, true),
            'raw_taxable_price' => $this->getTaxablePrice(false),
            'raw_taxable_leasing_price' => $this->getTaxablePrice(false, true),
            'price' => $this->getPrice(),
            'leasing_price' => $this->getPrice(true, true),
            'tax' => $this->getTax(),
            'taxable_price' => $this->getTaxablePrice(),
            'taxable_leasing_price' => $this->getTaxablePrice(true, true),
            'location_price' => $this->getLocationPrice(),
            'location_leasing_price' => $this->getLocationPrice(true, true),
            'location_taxable_price' => $this->getTaxableLocationPrice(),
            'location_taxable_leasing_price' => $this->getTaxableLocationPrice(true),
            'attributes' => $this->attributes,
            'attributesGroups' => $this->attributesGroups,
            'package' => $this->twigPackage(),
            'published' => array_get($this->modelData, 'published', false)
        ];

        if(key_exists('thumb', $options)){
            $array['thumbs'] = [];
            foreach($this->images as $key => $image){
                $array['thumbs'][] = $this->imageThumbnail(array_merge([$key + 1], $options['thumb']));
            }
        }

        return $array;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name) {
        if($name == 'attributes')
            return $this->attributes;

        if($name == 'attributesGroups')
            return $this->attributesGroups;

        if($name == 'images')
            return $this->images;

        if($name == 'image')
            return $this->image;

        if($name == 'url')
            return $this->getUrl();

        if($name == 'price')
            return $this->getPrice(true);

        if($name == 'raw_price')
            return $this->getPrice(false);

        if($name == 'raw_taxable_price')
            return $this->getTaxablePrice(false);

        if($name == 'leasing_price')
            return $this->getPrice(true, true);

        if($name == 'tax')
            return $this->getTax();

        if($name == 'taxable_price')
            return $this->getTaxablePrice();

        if($name == 'location_price')
            return $this->getLocationPrice();

        if($name == 'taxable_location_price')
            return $this->getTaxableLocationPrice();

        if($name == 'package')
            return $this->twigPackage();

        if($name == 'category')
            return new Category($this->categoryModel);

        if($name == 'parent')
            return new Product($this->productModel->parent);

        return array_get($this->modelData, $name, null);
    }

    public function __call($name, $arguments){
        if($name == 'thumb')
            return $this->imageThumbnail($arguments);
        if($name == 'toArray')
            return $this->toArray($arguments);
        return '';
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name) {
        return true;
    }

    /**
     * @return Package
     */
    protected function twigPackage(){
        $package = $this->productModel->package;
        return new Package($package);
    }
}
