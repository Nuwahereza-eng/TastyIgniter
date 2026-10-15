<?php

declare(strict_types=1);

namespace Database\Seeders;

use Igniter\Cart\Models\Category;
use Igniter\Cart\Models\Menu;
use Illuminate\Database\Seeder;

class MenuContentSeeder extends Seeder
{
    /**
     * Ugandan dishes shown on the menu page. Images are read from the
     * committed storage/app/public/media/uploads directory.
     */
    protected array $dishes = [
        ['Matoke (Steamed Plantains)', 'Traditional Ugandan dish of steamed green bananas, cooked with groundnut sauce', 8000, 'steemed matoke.png', 3],
        ['Luwombo (Chicken)', 'Chicken stew steamed in banana leaves with vegetables and groundnuts', 12000, 'luwobo.png'],
        ['Posho & Beans', 'Maize flour porridge served with red beans cooked in tomato sauce', 5000, 'posho beans.png'],
        ['Rolex (Chapati Roll)', 'Popular street food: chapati rolled with eggs, tomatoes, onions and cabbage', 3000, 'Uganda-rolex-street-food.jpg'],
        ['Katogo (Matooke & Offals)', 'Breakfast dish of mashed matooke mixed with offals in groundnut sauce', 7000, 'katogo.png'],
        ['Nile Perch (Empuuta)', 'Fresh Nile perch grilled or fried, served with steamed vegetables', 15000, 'nile perch.png'],
        ['Groundnut Stew (Ebinyebwa)', 'Rich peanut butter stew with mushrooms, served with matooke or rice', 9000, 'gnuts.png'],
        ['Muchomo (Grilled Meat)', 'Skewered grilled goat or beef, seasoned with traditional spices', 10000, 'muchomo.png'],
        ['Kikomando', 'Pieces of chapati mixed with fried beans, topped with avocado', 4000, 'kikomando.jpeg'],
        ['Samosas (Sambusa)', 'Crispy fried pastry filled with spiced minced meat or vegetables', 2000, 'samosa.png'],
        ['Malewa (Bamboo Shoots)', 'Steamed bamboo shoots cooked in groundnut sauce - Eastern Uganda specialty', 8500, 'bamboo.png'],
        ['Eshabwe (Ghee Sauce)', 'Traditional butter sauce from Western Uganda, served with millet bread', 11000, 'eshabwe.png'],
    ];

    /**
     * Runs on every container start: only creates dishes that are missing and
     * only attaches an image when a dish has none, so admin edits are kept.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['name' => 'Traditional'],
            ['priority' => 5, 'status' => 1]
        );

        $mediaDirectory = storage_path('app/public/media/uploads');

        foreach ($this->dishes as $dish) {
            [$name, $description, $price, $image] = $dish;

            $menu = Menu::firstOrCreate(
                ['menu_name' => $name],
                [
                    'menu_description' => $description,
                    'menu_price' => $price,
                    'minimum_qty' => $dish[4] ?? 1,
                    'menu_status' => 1,
                ]
            );

            if ($menu->wasRecentlyCreated) {
                $menu->categories()->syncWithoutDetaching([$category->getKey()]);
            }

            $path = $mediaDirectory.DIRECTORY_SEPARATOR.$image;
            $media = $menu->getFirstMedia('thumb');
            if (($media && file_exists($media->getFullDiskPath())) || !file_exists($path)) {
                continue;
            }

            $media?->delete();

            $media = $menu->newMediaInstance();
            $media->addFromFile($path, 'thumb');
            $media->save();
            $menu->media()->save($media);
        }
    }
}
