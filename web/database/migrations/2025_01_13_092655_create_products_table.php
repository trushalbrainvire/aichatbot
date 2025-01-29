<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('product_id');
            $table->string('graphql_id');
            $table->string('title');
            $table->longText('body');
            $table->string('handle');
            $table->string('productType');
            $table->string('vendor');
            $table->float('price');
            $table->float('comparedAtPrice');
            $table->json('tags');
            $table->string('onlineStoreUrl')->nullable();
            $table->json('options_and_values');
            $table->foreignUuid('merchant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
