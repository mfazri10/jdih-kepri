<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('filename', 255);
            $table->string('original_name', 255);
            $table->string('file_url', 500);
            $table->string('file_path', 500);
            $table->unsignedBigInteger('file_size'); // dalam bytes
            $table->string('mime_type', 100); // application/pdf, dll
            $table->integer('sort_order')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index(['document_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
