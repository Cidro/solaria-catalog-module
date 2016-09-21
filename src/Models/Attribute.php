<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class Attribute extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_attributes';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo('Solaria\Models\Site', 'site_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Category', 'module_catalog_attributes_categories', 'attribute_id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\AttributeGroup', 'module_catalog_attributes_groups_attributes', 'attribute_id', 'attribute_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\AttributeData', 'attribute_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\AttributeValue', 'attribute_id', 'id');
    }
}