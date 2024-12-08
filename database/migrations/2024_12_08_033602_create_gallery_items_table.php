<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Enums\GalleryItemTypeEnum;
use Modules\Content\Models\Gallery;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('description')->nullable();
            $table->text('iframe')->nullable();
            $table->string('short_description')->nullable();
            $table->boolean('inline_video')->default(false);
            $table->integer('order')->default(500);
            $table->enum('type', GalleryItemTypeEnum::values());
            $table->foreignIdFor(Gallery::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
