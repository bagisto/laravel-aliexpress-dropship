<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipAliExpressOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_order_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ali_express_product_id')->unsigned();
            $table->foreign('ali_express_product_id')->references('id')->on('dropship_ali_express_products')->onDelete('cascade');

            $table->integer('order_item_id')->unsigned();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->integer('ali_express_order_id')->unsigned();
            $table->foreign('ali_express_order_id')->references('id')->on('dropship_ali_express_orders')->onDelete('cascade');

            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('dropship_ali_express_order_items')->onDelete('cascade');

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
        Schema::dropIfExists('dropship_ali_express_order_items');
    }
}
