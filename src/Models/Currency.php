<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

use Solaria\Models\SolariaModel;

class Currency extends SolariaModel {

    protected $table = 'module_catalog_currencies';

    protected $casts = [
        'default' => 'boolean',
    ];

    public function format($value){
        return $this->symbol . number_format($value, $this->precision, $this->decimal_point, $this->thousands_separator);
    }
}