<?php

namespace App\Libraries;

use Ramsey\Uuid\Uuid;
use App\Models\Review;
use App\Models\Talent;
use App\Models\FileModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImgMap;

/**
 * File library, we can easy switch local or aws filesystem.
 *
 * Class FilesLibrary.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Libraries
 */
class FilesLibrary
{
    private $rootDir = 'public/';

    /**
     * Untuk simpan images.
     *
     * @param $image
     * @param $dir
     * @param $moduleType
     * @param $standardWidth
     * @param $standardHeight
     * @param $autoRotate
     * @return mixed|string
     * @throws \Exception
     */
    public function saveImage(
        $image,
        $dir,
        $autoRotate = false,
        $standardWidth = 750,
        $standardHeight = 410,
        $moduleType = 'user'
    ) {

        $id = Uuid::uuid4()->toString();
        $resize = $this->resize($image, $standardWidth, $standardHeight, $autoRotate);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        // $modelImage = new FileModel();
        // $modelImage->id = $id;
        // $modelImage->module_type = $moduleType;
        // $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        // $modelImage->path = $full;
        // // $modelImage->path = $this->rootDir . '/' . $dir;

        // $modelImage->created_at = time();
        // $modelImage->save();
        // $imageId = $modelImage->id;

        return $full;
    }

    public function saveImageAspectRatio($image_profile1, $image_profile2, $image_profile3, $image_profile4,  $image, $dir, $moduleType = 'user', $sizeWidthHeight = 700)
    {
        $imageProfiles = [$image_profile1, $image_profile2, $image_profile3, $image_profile4];

        foreach ($imageProfiles as $imageProfileName) {
            if ($imageProfileName) {
                Storage::disk()->delete($imageProfileName);
            }
        }
        $id = Uuid::uuid4()->toString();
        $resize = $this->resizeAspectRatio($image, $sizeWidthHeight);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . '/' . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        //         $modelImage = new FileModel();
        //         $modelImage->id = $id;
        //         $modelImage->module_type = $moduleType;
        //         $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        //         $modelImage->path = $full;
        // //        $modelImage->path = $this->rootDir . '/' . $dir;

        //         $modelImage->created_at = time();
        //         $modelImage->save();
        //         $imageId = $modelImage->id;

        return $full;
    }

    /**
     * Untuk simpan photo profile.
     *
     * @param $image
     * @param $dir
     * @param $moduleType
     * @param $resize
     * @return mixed|string
     * @throws \Exception
     */
    public function saveUserImage($image, $dir, $moduleType = 'user')
    {

        $id = Uuid::uuid4()->toString();
        $resize = $this->resize($image, 128, 128, false);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . '/' . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        // $modelImage = new FileModel();
        // $modelImage->id = $id;
        // $modelImage->module_type = $moduleType;
        // $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        // $modelImage->path = $this->rootDir . $dir;
        // $modelImage->file_url = $full;
        // $modelImage->created_at = time();
        // $modelImage->save();
        // $imageId = $modelImage->id;

        return $full;
    }

    /**
     * Simpan data selain file images.
     *
     * @param $file
     * @param $dir
     * @return array
     * @throws \Exception
     */
    public function saveFileAttachment($file, $dir)
    {

        $id = Uuid::uuid4()->toString();
        $ext = $file->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, file_get_contents($file));
        // $modelImage = new FileModel();
        // $modelImage->id = $id;
        // $modelImage->module_type = 'attachment';
        // $modelImage->extension = Str::slug($ext);
        // $modelImage->file_url = $full;

        // $modelImage->path = $this->rootDir . '/' . $dir;
        // $modelImage->created_at = time();
        // $modelImage->save();

        // //Return save id;
        // $imageId = $modelImage->id;

        return [
            'id' => $full,
            'type' => Str::slug($file->getClientOriginalExtension()),
        ];
    }

    /**
     * @param $dbPath
     * @return mixed
     */
    public static function getAttachment($dbPath)
    {
        return Storage::disk()->url($dbPath);
    }

    public static function delete(Talent $model)
    {
        Storage::disk()->delete($model->image_profile);
        Storage::disk()->delete($model->image_profile2);
        Storage::disk()->delete($model->image_profile3);
        Storage::disk()->delete($model->image_profile4);
    }

    public static function deleteReview($model)
    {
        // dd($model);
        $reviewPhotos = [$model->review_photo, $model->review_photo2, $model->review_photo3, $model->review_photo4];

        foreach ($reviewPhotos as $reviewPhoto) {
            if ($reviewPhoto) {
                Storage::disk()->delete($reviewPhoto);
            }
        }
    }

    private function resizeAspectRatio($rawImage, $sizeWidthHeight = 700)
    {
        $image = ImgMap::make($rawImage);
        $width = $image->getWidth();
        $height = $image->getHeight();
        if ($width > $height) {
            // landscape
            if ($width > $sizeWidthHeight) {
                $image = $image->resize($sizeWidthHeight, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } else {
            // portrait
            if ($height > $sizeWidthHeight) {
                $image = $image->resize(null, $sizeWidthHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        return $image;
    }

    public function resize($raw, $standardWidth = 750, $standardHeight = 410, $autoRotate = true)
    {
        $standard = 750;
        $image = ImgMap::make($raw);
        if (!$autoRotate) {
            $image = $image->resize($standardWidth, $standardHeight);
            return $image;
        }
        $width = $image->getWidth();
        $height = $image->getHeight();
        if ($width > $height) {
            // landscape
            if ($width > $standard) {
                $image = $image->resize($standard, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } else {
            // portrait
            if ($height > $standard) {
                $image = $image->resize(null, $standard, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        return $image;
    }

    /**
     * Converts bytes into human readable file size.
     *
     * @param string $bytes
     * @return string human readable file size (2,87 Мб)
     * @author Mogilev Arseny
     */
    public static function fileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = [
            0 => [
                'UNIT' => 'TB',
                'VALUE' => pow(1024, 4),
            ],
            1 => [
                'UNIT' => 'GB',
                'VALUE' => pow(1024, 3),
            ],
            2 => [
                'UNIT' => 'MB',
                'VALUE' => pow(1024, 2),
            ],
            3 => [
                'UNIT' => 'KB',
                'VALUE' => 1024,
            ],
            4 => [
                'UNIT' => 'B',
                'VALUE' => 1,
            ],
        ];

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem['VALUE']) {
                $result = $bytes / $arItem['VALUE'];
                $result = round($result, 2) . $arItem['UNIT'];
                break;
            }
        }

        return $result;
    }
}