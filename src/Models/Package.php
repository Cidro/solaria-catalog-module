<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class Package extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_packages';

    protected $fillable = ['code', 'published', 'ordering'];

    protected $casts = [
        'published' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo('Solaria\Models\Site', 'site_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Product', 'module_catalog_products_packages', 'package_id', 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function childPackages(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Package', 'module_catalog_packages_packages', 'parent_package_id', 'child_package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentPackages(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Package', 'module_catalog_packages_packages', 'child_package_id', 'parent_package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Category', 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directProduct(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Product', 'package_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\PackageData', 'package_id', 'id');
    }

    /**
     * @param $value
     */
    public function setImagesAttribute($value){
        $this->attributes['images'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getImagesAttribute($value){
        return json_decode($value);
    }
}