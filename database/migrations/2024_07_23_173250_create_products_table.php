<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('category_id')->unsigned()->nullable();
			$table->string('name')->unique();
			$table->text('description');
			$table->decimal('price', 15, 2);
			$table->integer('stock')->unsigned()->nullable()->default(0);
            $table->timestamps();
			$table->softDeletes();

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
