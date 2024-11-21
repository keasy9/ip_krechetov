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
        Schema::create('page_partials', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('page_code')->index();
            $table->string('slug')->index();
            $table->text('value')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_partials');
    }
};
