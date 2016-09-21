<?php

namespace Asimov\Solaria\Modules\Catalog\Models;

interface TranslatableInterface {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data();
}