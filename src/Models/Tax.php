<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Solaria\Models\SolariaModel;

class Tax extends SolariaModel {

    protected $table = 'module_catalog_taxes';

    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * @param $value
     * @return mixed
     */
    public function add($value){
        $value = (double)$value;
        return $value + $this->get($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function get($value){
        return $value * $this->value;
    }

    /**
     * @param $value
     * @return float
     */
    public function remove($value){
        return $value / (1 + $this->value);
    }
}