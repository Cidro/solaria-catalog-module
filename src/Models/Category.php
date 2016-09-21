<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class Category extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_categories';

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo('Solaria\Models\Site', 'site_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page(){
        return $this->belongsTo('Solaria\Models\Page', 'page_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Category', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Category', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Product', 'category_id', 'id')->with('locations')->orderBy('ordering', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Package', 'category_id', 'id')->orderBy('ordering', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributesGroups(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\AttributeGroup', 'module_catalog_attributes_groups_categories', 'category_id', 'attribute_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return  $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\CategoryData', 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layout(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Layout', 'layout_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productLayout(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Layout', 'product_layout_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packageLayout(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Layout', 'package_layout_id', 'id');
    }

    /**
     * @param $value
     */
    public function setImagesAttribute($value){
        $this->attributes['images'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function toArray() {
        $array = parent::toArray();
        $array['alias'] = str_slug(array_get($array, 'name', ''));
        return $array;
    }


    /**
     * @param $value
     * @return mixed
     */
    public function getImagesAttribute($value){
        return json_decode($value);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getWithChildrens(){
        $categories = collect();
        $categories->push($this);
        if($this->children){
            /** @var Category $child */
            foreach($this->children as $child){
                $categories = $categories->merge($child->getWithChildrens());
            }
        }
        return $categories;
    }
}