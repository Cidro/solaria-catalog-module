<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class CategoryData extends DataModel {

    protected $table = 'module_catalog_category_data';

    public function category(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Category', 'id', 'category_id');
    }

}