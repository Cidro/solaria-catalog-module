<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class PackageData extends DataModel {

    protected $table = 'module_catalog_package_data';

    public function package(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Package', 'id', 'package_id');
    }

}