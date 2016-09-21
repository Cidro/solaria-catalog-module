<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class LocationData extends DataModel {

    protected $table = 'module_catalog_location_data';

    public function location(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Location', 'id', 'location_id');
    }

}