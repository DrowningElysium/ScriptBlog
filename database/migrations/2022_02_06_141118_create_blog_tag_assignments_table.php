<?php

use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTagAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('blog_tag_assignments', static function (Blueprint $table) {
            $table->foreignIdFor(Blog::class, 'blog_id')->constrained('blogs');
            $table->foreignIdFor(BlogTag::class, 'blog_tag_id')->constrained('blog_tags');

            $table->primary(['blog_id', 'blog_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tag_assignments');
    }
}
