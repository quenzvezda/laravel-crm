<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Signature extends Model
{
    protected $fillable = [
        'name',
        'owner_name',
        'image_path',
        'is_active',
    ];

    /**
     * Get the full URL for the signature image.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }

        return null;
    }
}
