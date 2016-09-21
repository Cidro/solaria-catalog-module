<?php
namespace Asimov\Solaria\Modules\Catalog\Models\Twig;

use Asimov\Solaria\Modules\Catalog\Models\Category as CategoryModel;
use Illuminate\Support\Collection;

class Category extends BaseTwig{

    /**
     * @var Collection
     */
    protected $products = null;

    /**
     * @var Collection
     */
    protected $subCategories;

    /**
     * @var CategoryModel
     */
    protected $categoryModel;
    /**
     * @var array
     */
    protected $images = [];

    /**
     * @var null
     */
    protected $image = null;

    /**
     * Category constructor.
     * @param CategoryModel $category
     */
    public function __construct($category) {
        parent::__construct();
        $this->products = collect();
        $this->subCategories = collect();
        $this->categoryModel = $category;
        $this->modelData = $category->toArray();
    }

    public function toArray($arguments = []){
        $options = array_get($arguments, '0', []);
        $array = [
            'id' => array_get($this->modelData, 'id'),
            'name' => array_get($this->modelData, 'name'),
            'description' => array_get($this->modelData, 'description'),
            'alias' => array_get($this->modelData, 'alias'),
            'images' => $this->images,
            'image' => $this->image,
            'translations' => array_get($this->modelData, 'data.translations'),
            'categories' => $this->twigCategories(true, array_get($arguments, 0, [])),
            'products' => $this->twigProducts(true, array_get($arguments, 0, [])),
            'attributeGroups' => $this->attributeGroups()
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
    public function __get($name){
        if($name == 'products')
            return $this->twigProducts();

        if($name == 'categories')
            return $this->twigCategories();

        if($name == 'images')
            return $this->images;

        if($name == 'image')
            return $this->image;

        if($name == 'alias')
            return str_slug(array_get($this->modelData, 'name'));

        return array_get($this->modelData, $name);
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
    public function __isset($name){
        return true;
    }

    protected function twigProducts($toArray = false, $toArrayOptions = []){
        if($this->products->isEmpty()){
            if($this->locationFilter){
                $products = $this->categoryModel->products()->whereHas('locations', function($query) {
                    if(key_exists('sub-location', $this->locationFilter))
                        $query->where(['id' => $this->locationFilter['sub-location']]);
                    elseif(key_exists('location', $this->locationFilter))
                        $query->where(['id' => $this->locationFilter['location']]);
                })->get();
            } else {
                $products = $this->categoryModel->products;
            }

            foreach ($products as $product) {
                $twigProduct = new Product($product);
                $this->products->push($toArray ? $twigProduct->toArray($toArrayOptions) : $twigProduct);
            }
        }
        return $this->products;
    }

    protected function twigCategories($toArray = false, $toArrayOptions = []){
        if($this->subCategories->isEmpty()){
            foreach ($this->categoryModel->children as $subCategory) {
                $twigCategory = new Category($subCategory);
                $this->subCategories->push($toArray ? $twigCategory->toArray($toArrayOptions) : $twigCategory);
            }
        }
        return $this->subCategories;
    }

    private function attributeGroups() {
        $attributeGroups = $this->categoryModel->attributesGroups()->with('attributes')->get();
        return $attributeGroups->toArray();
    }
}