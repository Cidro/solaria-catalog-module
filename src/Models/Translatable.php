<?php

namespace Asimov\Solaria\Modules\Catalog\Models;

use App;
use Solaria\Models\SolariaModel;

class Translatable extends SolariaModel {

    protected $languageableData = ['name', 'description'];

    public function toArray() {
        $array = array_merge(array_flip($this->languageableData), parent::toArray());
        $array['data'] = ['translations' => []];
        foreach (App::make('site')->languages as $language) {
            $translatedData = $this->data->filter(function($item) use($language){
                return $item->language_id == $language->id;
            })->first();

            if($translatedData){
                $translatedData = array_intersect_key($translatedData->toArray(), array_flip($this->languageableData));
                $array['data']['translations'][$language->code] = [
                    'data' => $translatedData
                ];
                if($language->id == App::make('site')->getLanguage()->id){
                    $array = array_merge($array, $translatedData);
                }
            }
        }
        $array['alias'] = str_slug(array_get($array, 'name', ''));
        return $array;
    }

    /**
     * @param $data
     * @param null $overrideLanguage
     */
    public function setData($data, $overrideLanguage = null){
        if($translations = array_get($data, 'translations', null)){
            foreach ($translations as $languageCode => $translation) {
                if($overrideLanguage){
                    $language = $overrideLanguage;
                } else {
                    $language = App::make('site')->languages()->where([
                        'code' => $languageCode
                    ])->first();
                }
                $translatedData = $this->data()->firstOrCreate([
                    'language_id' => $language->id
                ]);
                $translatedData->name = array_get($translation, 'data.name');
                $translatedData->description = array_get($translation, 'data.description');
                $translatedData->save();
            }
        }
    }
}