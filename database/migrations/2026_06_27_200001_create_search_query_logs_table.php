<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_query_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query', 255);
            $table->integer('results_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->json('filters')->nullable(); // filter yang dipakai
            $table->timestamp('searched_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index('query');
            $table->index('searched_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_query_logs');
    }
};
