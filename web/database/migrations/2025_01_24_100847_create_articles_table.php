<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('article_id');
            $table->string('graphql_id');
            $table->string('title');
            $table->longText('body');
            $table->string('handle');
            $table->string('articleType');
            $table->string('author');
            $table->foreignUuid('merchant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
