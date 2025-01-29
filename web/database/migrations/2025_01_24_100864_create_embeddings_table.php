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
        Schema::create('embeddings', function (Blueprint $table) {
            $table->id();
            $table->vector('vectors', 1536);
            $table->json('metadata');
            $table->uuidMorphs('embeddable');
            $table->foreignUuid('merchant_id');
            $table->timestamps();
        });

        DB::statement('CREATE INDEX my_index ON embeddings USING ivfflat (vectors vector_l2_ops) WITH (lists = 100)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};
