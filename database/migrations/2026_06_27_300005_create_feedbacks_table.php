<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('type', ['saran', 'masalah', 'pujian']);
            $table->string('subject', 255);
            $table->text('message');
            $table->tinyInteger('rating')->nullable(); // 1-5 bintang
            $table->string('page_url', 500)->nullable(); // halaman saat kirim
            $table->enum('status', ['new', 'read', 'resolved'])->default('new');
            $table->text('admin_reply')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
