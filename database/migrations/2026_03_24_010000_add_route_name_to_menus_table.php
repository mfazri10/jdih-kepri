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
        Schema::table('menus', function (Blueprint $table): void {
            $table->string('route_name')->nullable()->after('alamat_url');
            $table->index('route_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table): void {
            $table->dropIndex(['route_name']);
            $table->dropColumn('route_name');
        });
    }
};
