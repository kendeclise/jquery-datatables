<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $categories = App\Category::pluck('id')->toArray();
    $buyPrice = $faker->randomFloat(2, 250, 3000);
    $gain = $faker->randomFloat(2, 20, 70);
    $sellPrice = $buyPrice + $gain;
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence,
        'buy_price' => $buyPrice,
        'sell_price' => $sellPrice,
        'stock' => $faker->numberBetween(0, 100),
        'category_id' => $faker->randomElement($categories)
    ];
});
