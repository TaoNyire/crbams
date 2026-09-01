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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->string('serial_number')->nullable()->unique();

            $table->foreignId('asset_category_id')
                ->constrained('asset_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('asset_type_id')
                ->constrained('asset_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('location')->nullable();

            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('supplier')->nullable();

            $table->enum('condition', [
                'new',
                'good',
                'fair',
                'poor',
                'damaged',
            ])->default('new');

            $table->enum('status', [
                'available',
                'assigned',
                'under_repair',
                'disposed',
                'lost',
                'retired',
            ])->default('available');

            $table->string('barcode')->nullable()->unique();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
