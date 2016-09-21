<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class AttributeGroupData extends DataModel {

    protected $table = 'module_catalog_attribute_group_data';

    public function attribute(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\AttributeGroup', 'id', 'attribute_group_id');
    }

}