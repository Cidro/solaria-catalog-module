<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

use App;

class Product extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_products';

    protected $fillable = ['code', 'sku', 'price', 'leasing_price', 'published', 'ordering', 'images'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Product', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Product', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page(){
        return $this->belongsTo('Solaria\Models\Page', 'page_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return  $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\ProductData', 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attributes(){
        return $this->morphMany('Asimov\Solaria\Modules\Catalog\Models\AttributeValue', 'attributable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Category', 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function package(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Package', 'package_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Package', 'module_catalog_products_packages', 'product_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function locations(){
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Location', 'module_catalog_products_locations', 'product_id', 'location_id')
            ->withPivot('price', 'leasing_price');
    }

    /**
     * @param $value
     */
    public function setImagesAttribute($value){
        if(gettype($value) === 'object')
            $value = [object_get($value, '0')];
        $this->attributes['images'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getImagesAttribute($value){
        return json_decode($value);
    }

    /**
     * @param $locationsIds
     * @param $locationsPrices
     */
    public function syncLocations($locationsIds, $locationsPrices){
        $locationsSync = [];
        foreach ($locationsIds as $locationId)
            $locationsSync[$locationId] = [
                'price' => array_get($locationsPrices, $locationId.'.price', 0),
                'leasing_price' => array_get($locationsPrices, $locationId.'.leasing_price', 0),
            ];

        $this->locations()->sync($locationsSync);
    }

    /**
     * @return array
     */
    public function getLocationsPrices(){
        $locationsPrices = [];
        foreach ($this->locations as $location) {
            $locationsPrices[$location->id] = $location->pivot->price;
        }
        return $locationsPrices;
    }

    /**
     * @param $attributes
     */
    public function setAttributes($attributes){
        $updatedIds = [];
        foreach ($attributes as $attribute) {
            if(array_get($attribute, 'visible') == true){
                if($translations = array_get($attribute, 'data.translations', null)){
                    foreach ($translations as $languageCode => $translation) {
                        $language = App::make('site')->languages()->where([
                            'code' => $languageCode
                        ])->first();
                        $translatedAttribute = $this->attributes()->firstOrCreate([
                            'language_id' => $language->id,
                            'attribute_id' => array_get($attribute, 'id')
                        ]);
                        $translatedAttribute->value = array_get($translation, 'data.value');
                        $translatedAttribute->save();
                        $updatedIds[] = $translatedAttribute->attribute_id;
                    }
                }
            }
        }
        $this->attributes()->whereNotIn('attribute_id', array_unique($updatedIds))->delete();
    }

    /**
     * @return array
     */
    public function toArray() {
        $array = parent::toArray();
        if(key_exists('attributes', $array)){
            foreach ($array['attributes'] as &$attribute) {
                if(key_exists('attribute', $attribute)){
                    $attribute['alias'] = array_get($attribute, 'attribute.alias', '');
                }
            }
        }
        return $array;
    }


}