<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropshipAliExpressProductVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_product_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url');
            $table->integer('product_video_id')->unsigned();
            $table->foreign('product_video_id')->references('id')->on('product_videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropship_ali_express_product_videos');
    }
}
