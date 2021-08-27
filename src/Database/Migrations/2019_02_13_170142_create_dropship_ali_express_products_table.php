<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipAliExpressProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ali_express_product_url')->nullable();
            $table->string('ali_express_product_description_url')->nullable();
            $table->string('ali_express_product_id')->nullable();
            $table->string('combination_id')->nullable();

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('dropship_ali_express_products')->onDelete('cascade');

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
        Schema::dropIfExists('dropship_ali_express_products');
    }
}
