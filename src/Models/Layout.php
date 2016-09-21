<?php

namespace Asimov\Solaria\Modules\Catalog\Models;

use Log;
use Solaria\Models\SolariaModel;
use Storage;

class Layout extends SolariaModel {
    public $old_alias = '';

    public $html = '';

    protected $table = 'module_catalog_layouts';

    /**
     * Obtiene el conteido del archivo html asociado al layout
     * @return string
     */
    public function getHtmlAttribute(){
        $html_file_extension = '.' . config('twigbridge.twig.extension', 'twig');
        $html_content = '';
        if(Storage::drive('vendor_views')->exists($this->getTemplateFolderName() . $this->alias . $html_file_extension))
            $html_content = Storage::drive('vendor_views')->get($this->getTemplateFolderName() . $this->alias . $html_file_extension);
        return $html_content;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(){
        return $this->belongsTo('Solaria\Models\Site', 'site_id', 'id');
    }

    /**
     * @param bool $old
     * @return string
     */
    public function getTemplateFolderName($old = false){
        return '/modulecatalog/' . $this->site->alias . '/layouts/';
    }

    /**
     * Obtiene la carpeta de la vista del layout
     * @return string
     */
    public function getLayoutViewFolder(){
        return '/vendor' . $this->getTemplateFolderName(). $this->alias;
    }

    /**
     * Guarda el nombre anterior del layout en caso de que se cambie
     * @param $alias
     */
    public function setAliasAttribute($alias){
        $this->old_alias = $this->alias;
        $this->attributes['alias'] = $alias;
    }

    /**
     * @return array
     */
    public function toArray() {
        $array = parent::toArray();
        $array['html'] = $this->getHtmlAttribute();
        return $array;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = []) {
        $html_file_extension = '.' . config('twigbridge.twig.extension', 'twig');

        if($this->exists && $this->old_alias != $this->alias)
            Storage::drive('vendor_views')->delete($this->getTemplateFolderName() . $this->old_alias . $html_file_extension);

        Storage::drive('vendor_views')->put($this->getTemplateFolderName() . $this->alias . $html_file_extension, $this->html);

        try {
            chmod(config('filesystems.disks.vendor_views.root') . '/' . $this->getTemplateFolderName(), 0775);
            chmod(config('filesystems.disks.vendor_views.root') . '/' . $this->getTemplateFolderName() . $this->alias . $html_file_extension, 0664);
        } catch(\Exception $e){
            Log::error($e->getMessage());
        }

        return parent::save($options);
    }

    public function delete() {
        $html_file_extension = '.' . config('twigbridge.twig.extension', 'twig');
        Storage::drive('vendor_views')->delete($this->getTemplateFolderName() . $this->alias . $html_file_extension);
        return parent::delete();
    }


}