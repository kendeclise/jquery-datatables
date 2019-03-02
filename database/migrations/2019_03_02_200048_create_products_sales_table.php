<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_sales', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('sale_id');
            $table->unsignedTinyInteger('quantity');
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('product_id')->references('id')->on('products');
        });

        DB::unprepared('ALTER TABLE `products_sales` ADD PRIMARY KEY (  `sale_id` ,  `product_id` )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_sales');
    }
}
