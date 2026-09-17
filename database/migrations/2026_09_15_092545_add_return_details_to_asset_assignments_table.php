<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->string('returned_condition')
                ->nullable()
                ->after('returned_at');

            $table->text('return_notes')
                ->nullable()
                ->after('returned_condition');
        });
    }

    public function down(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'returned_condition',
                'return_notes',
            ]);
        });
    }
};