<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique(); // "UU", "PP", "PERDA", dll
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Self-referencing FK setelah tabel dibuat
        Schema::table('document_types', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('document_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
