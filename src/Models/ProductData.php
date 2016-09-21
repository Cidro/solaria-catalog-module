<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class ProductData extends DataModel {

    protected $table = 'module_catalog_product_data';

    public function product(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Product', 'id', 'product_id');
    }

    public function language(){
        return $this->belongsTo('Solaria\Models\Language', 'language_id', 'id');
    }

}