<?php

declare(strict_types=1);

namespace Database\Seeders;

use Igniter\Frontend\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontendContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slider = Slider::firstOrCreate(
            ['code' => 'home-slider'],
            ['name' => 'Homepage slider']
        );

        $this->syncSliderImages($slider, [
            'slider.png',
            'slider2.png',
            'slider3.png',
            'slider4.png',
            'slider5.png',
        ]);
    }

    protected function syncSliderImages(Slider $slider, array $fileNames): void
    {
        DB::table('media_attachments')
            ->where('attachment_type', 'sliders')
            ->where('attachment_id', $slider->id)
            ->where('tag', 'images')
            ->delete();

        $mediaDirectory = storage_path('app/public/media/uploads');

        foreach ($fileNames as $fileName) {
            $path = $mediaDirectory.DIRECTORY_SEPARATOR.$fileName;

            if (!file_exists($path)) {
                continue;
            }

            $media = $slider->newMediaInstance();
            $media->addFromFile($path, 'images');
            $media->save();
            $slider->media()->save($media);
        }
    }
}