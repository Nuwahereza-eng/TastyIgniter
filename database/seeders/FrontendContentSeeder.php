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
        // Runs on every container start: leave the slider alone when its
        // images are already attached and their files exist on disk.
        if ($this->sliderImagesIntact($slider, count($fileNames))) {
            return;
        }

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

            // Pre-generate the hero thumbnail used by the demo theme. A failure
            // here must not stop the remaining slides from being attached.
            try {
                $media->getThumb([
                    'width' => 1920,
                    'height' => 800,
                    'fit' => 'contain',
                ]);
            } catch (\Throwable $e) {
                $this->command?->warn('Slider thumbnail failed for '.$fileName.': '.$e->getMessage());
            }
        }
    }

    protected function sliderImagesIntact(Slider $slider, int $expected): bool
    {
        $images = $slider->getMedia('images');

        if ($images->count() < $expected) {
            return false;
        }

        foreach ($images as $media) {
            if (!file_exists($media->getFullDiskPath())) {
                return false;
            }
        }

        return true;
    }
}