<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        // $this->call(UsersTableSeeder::class);

        //Creamos 25 categorias
        factory(\App\Category::class)->times(25)->create();
        //Creamos 80 productos
        factory(\App\Product::class)->times(80)->create();
        $productIds = App\Product::pluck('id')->toArray();

        //creamos 5000 ventas
        factory(\App\Sale::class)->times(5000)->create();

        $sales = \App\Sale::all();

        //Generamos sus detalle
        $frecuenciaCantidad = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 8, 9, 10];

        foreach ($sales as $sale)
        {
            $numberOfProducts = $faker->numberBetween(1, 3);
            $productsIdAux = $faker->randomElements($productIds, $numberOfProducts);

            for($i = 0; $i < count($productsIdAux); $i++)
            {
                $sale->products()->attach($productsIdAux[$i], ['quantity' => $faker->randomElement($frecuenciaCantidad)]);
            }
        }

    }
}
