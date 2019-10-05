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
            $table->string('image');
            $table->string('name');
            $table->decimal('price',7,2);
            $table->string('decription',45);
            $table->string('category');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);   
            $table->rememberToken();  
            $table->timestamps();
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
