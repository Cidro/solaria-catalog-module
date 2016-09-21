<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class AttributeData extends DataModel {

    protected $table = 'module_catalog_attribute_data';

    public function attribute(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Attribute', 'id', 'attribute_id');
    }

}