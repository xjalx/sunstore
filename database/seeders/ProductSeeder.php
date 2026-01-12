<?php

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductType::cases() as $type) {
            $this->importProducts($type);
        }
    }

    private function importProducts(ProductType $type): void
    {
        $csvFile = base_path('projectfiles/' . $type->csvFile());

        if (!File::exists($csvFile)) {
            $this->command->error("{$type->label()} CSV file not found: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        fgetcsv($file); // Skip header row

        while (($row = fgetcsv($file)) !== false) {
            $product = Product::create([
                'id' => $row[0],
                'type' => $type->value,
                'name' => $row[1],
                'manufacturer' => $row[2],
                'price' => $row[3],
                'description' => $row[5],
            ]);

            $attributes = $type->filterableAttributes();
            if (!empty($attributes)) {
                ProductAttribute::createWithValue(
                    $product->id,
                    $attributes[0]->value,
                    $row[4]
                );
            }
        }

        fclose($file);
        $this->command->info("{$type->label()}s imported successfully.");
    }
}
