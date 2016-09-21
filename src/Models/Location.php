<?php
namespace Asimov\Solaria\Modules\Catalog\Models;

class Location extends Translatable implements TranslatableInterface {

    protected $table = 'module_catalog_locations';

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
        return $this->belongsToMany('Asimov\Solaria\Modules\Catalog\Models\Product', 'module_catalog_products_locations', 'location_id', 'product_id')
            ->withPivot('price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children(){
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\Location', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('Asimov\Solaria\Modules\Catalog\Models\Location', 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data() {
        return $this->hasMany('Asimov\Solaria\Modules\Catalog\Models\LocationData', 'location_id', 'id');
    }

    /**
     * @return mixed
     */
    public function allChildren(){
        return $this->children()->with('allChildren');
    }

    /**
     * @param $parentId
     * @param $childrenIds
     * @return bool
     */
    public function checkParentAndChildren($parentId, $childrenIds){
        if($parentId && count($childrenIds)){
            foreach (Location::whereIn('id', $childrenIds)->get() as $directChild) {
                $children = $directChild->getWithChildren();
                foreach ($children as $child) {
                    if($parentId == $child->id)
                        return false;
                }
            }
        }
        return true;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getWithChildren(){
        $locations = collect();
        $locations->push($this);
        if($this->children){
            /** @var Location $child */
            foreach($this->children as $child){
                $locations = $locations->merge($child->getWithChildren());
            }
        }
        return $locations;
    }

    /**
     * @param $siteId
     * @return \Illuminate\Support\Collection|static
     */
    public static function allWithChildren($siteId){
        $locations = collect();
        $locationsModel = self::where(['site_id' => $siteId])
            ->whereNull('parent_id')
            ->get();

        /** @var Location[] $locationsModel */
        foreach ($locationsModel as $locationModel) {
            $locations = $locations->merge($locationModel->getWithChildren());
        }
        return $locations;
    }
}