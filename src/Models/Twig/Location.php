<?php
namespace Asimov\Solaria\Modules\Catalog\Models\Twig;

use Asimov\Solaria\Modules\Catalog\Models\Location as LocationModel;

class Location {

    /**
     * @var array
     */
    protected $products = [];

    /**
     * @var LocationModel
     */
    protected $locationModel;

    /**
     * @var array
     */
    protected $locationData;

    /**
     * Category constructor.
     * @param LocationModel $location
     */
    public function __construct($location) {
        $this->locationModel = $location;
        $this->locationData = $location ? $location->toArray() : [];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name){
        if($name == 'products')
            return $this->twigProducts();

        return array_get($this->locationData, $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name){
        return true;
    }

    /**
     * @return Product[]
     */
    protected function twigProducts(){
        if(!$this->products){
            foreach ($this->locationModel->products as $product) {
                $this->products[] = new Product($product);
            }
        }
        return $this->products;
    }
}