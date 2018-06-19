<?php

namespace Wqer1019\ImageUploader;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Wqer1019\ImageUploader\Resources\ImageResource;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Wqer1019\ImageUploader\Exceptions\ImageuploadException;
use Wqer1019\ImageUploader\Exceptions\NotAllowUploadException;

class Imageupload
{
    private $config;

    private $imageInfo;

    public function __construct(ImageManager $intervention, $config = [])
    {
        $this->intervention = $intervention;
        $this->config = $config;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return ImageResource
     * @throws ImageuploadException
     * @throws NotAllowUploadException
     */
    public function upload(UploadedFile $uploadedFile)
    {
        $this->prepareUpload($uploadedFile);

        // 獲取圖片Hash值
        $this->getImageHash($uploadedFile);

        // 圖片秒傳
        $image = Image::byHash($this->imageInfo['hash'])->limit(1);
        if (!!$image->count()) {
            $imageInfo = $image->first();
        } else {
            // 保存文件
            $imageInfo = $this->save($uploadedFile);
        }
        return new ImageResource($imageInfo);
    }

    /**
     * @throws ImageuploadException
     * @throws NotAllowUploadException
     */
    private function prepareUpload(UploadedFile $uploadedFile)
    {
        // 獲取圖片的屬性
        $this->getUploadedOriginalImageProperties($uploadedFile);

        // 是否為允許上傳的類型
        if (!in_array($this->imageInfo['original']['extension'], $this->config['allow_types'])) {
            throw new NotAllowUploadException('不允許上傳的類型');
        }
    }

    private function getUploadedOriginalImageProperties(UploadedFile $uploadedFile)
    {
        $this->imageInfo['original']['filename'] = $uploadedFile->getClientOriginalName();
        $this->imageInfo['original']['filepath'] = $uploadedFile->getRealPath();
        $this->imageInfo['original']['extension'] = $uploadedFile->getClientOriginalExtension();
        $this->imageInfo['original']['filesize'] = (int)$uploadedFile->getSize();
        $this->imageInfo['original']['mime'] = $uploadedFile->getMimeType();
        return $this;
    }

    private function getImageHash($uploadedFile)
    {
        $this->imageInfo['hash'] = md5_file($uploadedFile->getRealPath());
        return $this;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return $this
     * @throws ImageuploadException
     */
    private function save(UploadedFile $uploadedFile)
    {
        $image = $this->intervention->make($uploadedFile);
        $uploadedFile->storeAs(
            config('image_upload.path'),
            $this->imageInfo['hash'],
            ['disk' => $this->config['disk']]
        );
        if ($this->config['disk'] === 'local') {
            $url = Storage::disk(
                $this->config['disk'])
                ->url(config('image_upload.path') . '/' . $this->imageInfo['hash']);
            $this->imageInfo['cloud_url'] = $url;
        }
        $this->imageInfo['width'] = (int)$image->width();
        $this->imageInfo['height'] = (int)$image->height();
        return Image::create($this->wrap());
    }

    private function wrap()
    {
        return [
            'hash' => $this->imageInfo['hash'],
            'title' => $this->imageInfo['original']['filename'],
            'extension' => $this->imageInfo['original']['extension'],
            'width' => $this->imageInfo['width'],
            'height' => $this->imageInfo['height'],
            'mime' => $this->imageInfo['original']['mime'],
            'size' => $this->imageInfo['original']['filesize'],
            'cloud_url' => $this->imageInfo['cloud_url'] ?? null,
        ];
    }
}