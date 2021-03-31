<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'path', 'model_id', 'model_type', 'uuid', 'user_id', 'name', 'size'
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function getLinkAttribute()
    {
        return url(Storage::url($this->path));
    }
}
