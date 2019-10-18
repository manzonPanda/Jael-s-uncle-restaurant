<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_categories', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('category_id');
            $table->integer('quantity')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('category_id')->references('category_id')->on('categories');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_categories');
    }
}
