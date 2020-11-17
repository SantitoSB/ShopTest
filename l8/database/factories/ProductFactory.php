<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = category::all();
        $cat_id = collect($categories)->pluck('id')->random();

        return [
            'name' => $this->faker->jobTitle,
            'description' => $this->faker->realText(40),
            'price'=> mt_rand(10, 50),
            'category_id' => $cat_id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];
    }
}
