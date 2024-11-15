<?php

use App\Models\Professor;
use App\Models\University;
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
        Schema::create('professor_university', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Professor::class)->constrained();
            $table->foreignIdFor(University::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_university');
    }
};
