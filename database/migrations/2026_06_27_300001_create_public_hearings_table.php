<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_hearings', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->string('document_draft', 500)->nullable(); // URL draft rancangan
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['open', 'closed', 'archived'])->default('open');
            $table->string('location', 255)->nullable(); // lokasi offline
            $table->string('online_link', 500)->nullable(); // link zoom/meet
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_hearings');
    }
};
