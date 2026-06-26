<?php

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
        Schema::create('menus', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_menu');
            $table->string('nama_fitur')->nullable();
            $table->string('alamat_url')->nullable();
            $table->string('ikon')->nullable();
            $table->enum('tingkatan_menu', ['parent', 'child'])->default('parent');
            $table->unsignedInteger('urutan')->default(0);
            $table->foreignId('menu_induk_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('permission_name')->nullable();
            $table->string('tag')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['menu_induk_id', 'urutan']);
            $table->index('permission_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
