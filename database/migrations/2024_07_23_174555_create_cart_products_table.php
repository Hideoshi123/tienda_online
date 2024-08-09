<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('cart_id')->unsigned();
			$table->bigInteger('product_id')->unsigned();
			$table->integer('quantity')->unsigned()->nullable()->default(1);
            $table->timestamps();
			$table->softDeletes();

			$table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_products');
    }
};
