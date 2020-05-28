<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipAliExpressProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url');

            $table->integer('product_image_id')->unsigned();
            $table->foreign('product_image_id')->references('id')->on('product_images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropship_ali_express_product_images');
    }
}
