<?php

namespace Asimov\Solaria\Modules\Catalog\Models\Twig;

use Solaria\Models\Field\FieldImage;

class BaseTwig {

    /**
     * @var Image[]
     */
    protected $images = [];

    /**
     * @var null
     */
    protected $image = null;

    /**
     * @var array
     */
    protected $modelData;

    /**
     * @var array
     */
    protected $locationFilter;

    /**
     * BaseTwig constructor.
     */
    public function __construct() {
        $this->setLocationFilter();
    }

    /**
     * Prepara las imagenes del objeto
     */
    protected function prepareImages(){
        foreach ($this->modelData['images'] as $image) {
            $fieldImage = new FieldImage(new \stdClass(), $image->config);
            $this->images[] = $fieldImage->render();

            if(object_get($image, 'default', false))
                $this->image = $fieldImage->render();
        }
    }

    /**
     * @param $arguments
     * @return mixed
     */
    protected function imageThumbnail($arguments){
        $imageIndex = intval(array_get($arguments,0), 0) - 1;
        $width = array_get($arguments,1, null);
        $height = array_get($arguments,2, null);

        $image = $this->images[$imageIndex]->getImageModel();
        if($image){
            if($width && $height)
                return $image->resize('resize', [$width, $height]);
            if(!$width && $height)
                return $image->resize('height', [$height]);
            if($width && !$height)
                return $image->resize('width', [$width]);
            return $image->getPublicUrl();
        }
        return '';
    }

    /**
     * @return mixed
     */
    protected function setLocationFilter(){
        if(request()->get('sub-location', null))
            $this->locationFilter['sub-location'] = request()->get('sub-location', null);
        if(request()->get('location', null))
            $this->locationFilter['location'] = request()->get('location', null);
    }
}