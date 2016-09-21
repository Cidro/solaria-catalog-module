<?php

namespace Asimov\Solaria\Modules\Catalog\Models;

use Solaria\Models\SolariaModel;

class AttributeValue extends SolariaModel {

    protected $table = 'module_catalog_attributes_values';

    protected $fillable = ['language_id', 'attribute_id'];

    public $timestamps = false;

    public function attribute(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Attribute');
    }

    public function language(){
        return $this->belongsTo('Solaria\Models\Language', 'language_id', 'id');
    }

    public function attributable(){
        return $this->morphTo();
    }

}