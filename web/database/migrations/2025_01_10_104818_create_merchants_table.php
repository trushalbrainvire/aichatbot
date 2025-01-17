<?php

use App\Models\User;
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
        Schema::create('merchants', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('merchant_id');
            $table->string('store');
            $table->string('owner');
            $table->string('email');
            $table->string('currency_code');
            $table->json('currency_formats');
            $table->json('address');
            $table->string('domain');
            $table->string('storefront_password')->nullable();
            $table->foreignIdFor(User::class)->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
