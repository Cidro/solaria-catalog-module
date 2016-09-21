<?php

namespace Asimov\Solaria\Modules\Catalog\Models;

use Solaria\Models\SolariaModel;

class DataModel extends SolariaModel {
    protected $fillable = ['language_id'];

    public function language(){
        return $this->belongsTo('Solaria\Models\Language', 'language_id', 'id');
    }
}