<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class AttributeGroup extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_attributes_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo('Solaria\Models\Site', 'site_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\AttributeGroupData', 'attribute_group_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Attribute', 'module_catalog_attributes_groups_attributes', 'attribute_group_id', 'attribute_id');
    }
}