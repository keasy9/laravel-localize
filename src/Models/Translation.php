<?php

namespace Keasy9\Localize\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
    protected $guarded = [];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
