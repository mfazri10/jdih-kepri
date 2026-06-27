<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hearing_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hearing_id')->constrained('public_hearings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 100);
            $table->string('email', 255)->nullable();
            $table->string('institution', 150)->nullable(); // instansi/asal
            $table->text('opinion'); // pendapat/masukan
            $table->string('attachment_url', 500)->nullable(); // file pendukung
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['hearing_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hearing_submissions');
    }
};
