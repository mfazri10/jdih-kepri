<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('related_id')->constrained('documents')->cascadeOnDelete();
            $table->string('relation_type', 50)->default('terkait'); // 'mencabut','mengubah','diubah_oleh','terkait'
            $table->timestamps();

            $table->unique(['source_id', 'related_id', 'relation_type']);
            $table->index('related_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_relations');
    }
};
