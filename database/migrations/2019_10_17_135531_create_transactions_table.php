<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('transaction_id')->unique();
            $table->integer('tableId')->nullable();
            $table->string('customer_name',45)->nullable();
            // $table->integer('customer_id'); do this in the future Jake!:)
            $table->enum('status', ['PAID', 'PENDING']);   
            $table->decimal('totalPrice',7,2)->nullable();

            $table->foreign('tableId')->references('tableId')->on('tables');
            // $table->foreign('customer_id')->references('customer_id')->on('customers');
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
        Schema::dropIfExists('transactions');
    }
}
