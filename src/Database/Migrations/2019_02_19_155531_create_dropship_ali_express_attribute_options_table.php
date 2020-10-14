<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipAliExpressAttributeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_ali_express_attribute_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ali_express_attribute_option_id');
            $table->string('ali_express_swatch_name')->nullable();
            $table->string('ali_express_swatch_image')->nullable();

            $table->integer('ali_express_attribute_id')->unsigned();
            $table->foreign('ali_express_attribute_id', 'ali_attribute_options_attribute_id_foreign')->references('id')->on('dropship_ali_express_attributes')->onDelete('cascade');

            $table->integer('attribute_option_id')->unsigned();
            $table->foreign('attribute_option_id', 'ali_attribute_options_attribute_option_id_foreign')->references('id')->on('attribute_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropship_ali_express_attribute_options');
    }
}
