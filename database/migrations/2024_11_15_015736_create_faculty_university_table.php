<?php

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculty_university', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Faculty::class)->constrained();
            $table->foreignIdFor(University::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_university');
    }
};
