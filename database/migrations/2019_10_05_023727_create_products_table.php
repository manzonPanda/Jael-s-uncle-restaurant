<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            
            $table->integer('product_id')->autoIncrement()->unique();
            $table->string('image')->nullable();
            $table->string('name');
            $table->enum('size',['xs', 'small','medium','large','regular','xl','none']);
            // $table->integer('category_id');
            // $table->decimal('price',7,2)->nullable();
            $table->decimal('price',7,2)->default('0.00');
            $table->string('description',100)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE']);   
            $table->rememberToken();  
            $table->timestamps();

            // $table->foreign('category_id')->references('category_id')->on('categories');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
