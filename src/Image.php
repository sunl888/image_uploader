<?php

namespace Wqer1019\ImageUploader;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $fillable = ['hash', 'title', 'extension', 'width', 'height', 'mime', 'size', 'cloud_url'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('image_upload.table', 'images'));
    }

    public function scopeByHash($query, $hash)
    {
        return $query->whereHash($hash);
    }

    public function getImageUrlAttribute()
    {
        return image_url($this);
    }
}