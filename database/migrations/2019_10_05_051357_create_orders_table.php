<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('receiptNumber');
            $table->integer('product_id');
            $table->integer('customerName');
            // $table->integer('customer_id');
            $table->integer('quantity');
            $table->decimal('price',7,2);
            $table->enum('status', ['PAID', 'PENDING']);  
            $table->timestamps();

            // $table->foreign('product_id')->references('product_id')->on('products');
            // $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
