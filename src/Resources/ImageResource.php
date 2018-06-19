<?php

namespace Wqer1019\ImageUploader\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'hash' => $this->hash,
            'title' => $this->title,
            'extension' => $this->extension,
            'width' => $this->width,
            'height' => $this->height,
            'mime' => $this->mime,
            'size' => $this->size,
            'cloud_url' => $this->cloud_url,
            'image_url' => $this->image_url,
        ];
    }
}
