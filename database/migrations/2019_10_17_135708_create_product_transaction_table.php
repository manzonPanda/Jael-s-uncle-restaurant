<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_transact', function (Blueprint $table) {
            $table->integer('transaction_id');
            $table->integer('product_id');
            $table->integer('category_id');
            $table->integer('quantity');
            $table->decimal('price',7,2);
            $table->timestamps();
            
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_transact');
    }
}
