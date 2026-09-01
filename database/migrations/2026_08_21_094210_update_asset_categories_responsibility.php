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
        Schema::table('asset_categories', function (Blueprint $table) {
            $table->enum('responsible_officer', [
                'hardware',
                'administration',
            ])->default('administration')->after('name');
        });

        Schema::table('asset_categories', function (Blueprint $table) {
            $table->dropColumn('management_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_categories', function (Blueprint $table) {
            $table->enum('management_area', [
                'IT',
                'ADMINISTRATION',
            ])->nullable()->after('name');
        });

        Schema::table('asset_categories', function (Blueprint $table) {
            $table->dropColumn('responsible_officer');
        });
    }
};
