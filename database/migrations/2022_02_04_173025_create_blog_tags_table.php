<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('blog_tags', static function (Blueprint $table) {
            $table->id();
            // No user_id as I don't think we will have much benefit of knowing who made the tag
            $table->string('title', 30)->unique(); // Don't think it needs to be that long
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tags');
    }
}
