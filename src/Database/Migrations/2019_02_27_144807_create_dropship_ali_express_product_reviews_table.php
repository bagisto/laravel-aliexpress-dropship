<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipAliExpressProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ali_express_review_id');

            $table->integer('product_review_id')->unsigned();
            $table->foreign('product_review_id')->references('id')->on('product_reviews')->onDelete('cascade');

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
        Schema::dropIfExists('dropship_ali_express_product_reviews');
    }
}
