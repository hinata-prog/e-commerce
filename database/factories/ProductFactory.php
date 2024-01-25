<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->name();
        $slug = Str::slug($title);
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 80, 2000),
            'compare_price' => $this->faker->randomFloat(2, 200, 4000),
            'category_id' => function () {
                return \App\Models\Category::inRandomOrder()->first()->id;
            },
            'sub_category_id' => function (array $attributes) {
                // Get the category_id from the attributes array
                $categoryId = $attributes['category_id'];

                // Check if the category has sub-categories
                $hasSubCategories = \App\Models\SubCategory::where('category_id', $categoryId)->exists();

                if ($hasSubCategories) {
                    // Get a random sub-category that belongs to the same category
                    return \App\Models\SubCategory::where('category_id', $categoryId)
                        ->inRandomOrder()
                        ->first()
                        ->id;
                }

                // If no sub-categories, return null
                return null;
            },
            'brand_id' => function () {
                return \App\Models\Brand::inRandomOrder()->first()->id;
            },
            'is_featured' => $this->faker->randomElement(['Yes', 'No']),
            'sku' => $this->faker->unique()->uuid,
            'barcode' => $this->faker->ean13,
            'track_qty' => 'Yes',
            'qty' => $this->faker->randomNumber(2),
            'status' => 1,
        ];


    }
}
