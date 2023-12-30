<?php

namespace Keasy9\Localize\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Keasy9\CompositePk\Traits\HasCompositePrimaryKey;

class Translation extends Model
{
    use HasCompositePrimaryKey;
    protected $primaryKey = ['model_type', 'model_id', 'model_field', 'locale'];
    public $incrementing = false;
    protected $guarded = [];
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
