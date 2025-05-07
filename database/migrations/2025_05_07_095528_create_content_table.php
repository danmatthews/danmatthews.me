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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->string('slug');
            $table->boolean('published');
            $table->longText('content');
            $table->longText('excerpt')->nullable();
            $table->json('tags')->default('[]');
            $table->json('front_matter')->nullable();
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content');
    }
};
