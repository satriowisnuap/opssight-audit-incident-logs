<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description');

            $table->enum('severity', [
                'LOW',
                'MEDIUM',
                'HIGH',
                'CRITICAL'
            ]);

            $table->enum('status', [
                'OPEN',
                'IN_PROGRESS',
                'RESOLVED',
                'CLOSED'
            ])->default('OPEN');

            $table
                ->foreignId('category_id')
                ->constrained('incident_categories');

            $table
                ->foreignId('assigned_to')
                ->nullable()
                ->constrained('users');

            $table
                ->foreignId('reported_by')
                ->constrained('users');

            $table->timestamp('incident_date');
            $table->timestamp('resolved_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('severity');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
