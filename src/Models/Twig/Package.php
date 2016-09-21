<?php
namespace Asimov\Solaria\Modules\Catalog\Models\Twig;

use Asimov\Solaria\Modules\Catalog\Models\Package as PackageModel;
use Solaria\Models\Field\FieldImage;

class Package {

    /**
     * @var array
     */
    protected $products = [];

    /**
     * @var PackageModel
     */
    protected $packageModel;

    /**
     * @var array
     */
    protected $packageData;

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
     * @param PackageModel $package
     */
    public function __construct($package) {
        $this->packageModel = $package;
        $this->packageData = $package ? $package->toArray() : [];
    }

    protected function prepareImages(){
        foreach ($this->packageData['images'] as $image) {
            $fieldImage = new FieldImage(new \stdClass(), $image->config);
            $this->images[] = $fieldImage->render();

            if(object_get($image, 'default', false))
                $this->image = $fieldImage->render();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name){
        if($name == 'products')
            return $this->twigProducts();

        if($name == 'images')
            return $this->images;

        if($name == 'image')
            return $this->image;

        return array_get($this->packageData, $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name){
        return true;
    }

    protected function twigProducts(){
        if(!$this->products){
            foreach ($this->packageModel->products as $product) {
                $this->products[] = new Product($product);
            }
        }
        return $this->products;
    }
}