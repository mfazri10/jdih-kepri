<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('document_types')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('title'); // judul lengkap peraturan
            $table->string('number', 50); // "12" (nomor peraturan)
            $table->year('year'); // 2026
            $table->string('slug', 255)->unique(); // otomatis dari title+number+year
            $table->enum('status', ['berlaku', 'dicabut', 'tidak_berlaku'])->default('berlaku');
            $table->string('teu', 255); // Tajuk Entri Utama (BPHN standard)
            $table->string('subject', 255)->nullable(); // subjek hukum
            $table->string('source', 255)->nullable(); // sumber: DPRD, Gubernur, dll
            $table->string('signatory', 255)->nullable(); // penandatangan
            $table->string('place', 100)->nullable(); // tempat terbit: Tanjungpinang
            $table->date('date_set')->nullable(); // tanggal penetapan
            $table->date('date_publish')->nullable(); // tanggal pengundangan
            $table->date('date_effective')->nullable(); // tanggal berlaku
            $table->text('abstract')->nullable(); // abstrak/ringkasan
            $table->longText('full_text')->nullable(); // full text untuk search
            $table->string('language', 20)->default('id'); // bahasa dokumen
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type_id', 'year']);
            $table->index(['category_id', 'status']);
            $table->index(['year', 'status']);
            $table->index(['status', 'published_at']);
        });

        // FULLTEXT indexes (MySQL)
        DB::statement('ALTER TABLE documents ADD FULLTEXT INDEX ft_title (title)');
        DB::statement('ALTER TABLE documents ADD FULLTEXT INDEX ft_full_text (full_text)');
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
