<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information_requests', function (Blueprint $table) {
            $table->id();
            $table->string('register_number', 50)->unique(); // nomor register otomatis
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 100);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('institution', 150)->nullable();
            $table->enum('request_type', ['informasi', 'keberatan']);
            $table->string('subject', 255);
            $table->text('description');
            $table->text('response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->enum('status', ['pending', 'processing', 'fulfilled', 'rejected'])->default('pending');
            $table->date('due_date')->nullable(); // batas waktu (10 hari kerja)
            $table->string('attachment_url', 500)->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_requests');
    }
};
