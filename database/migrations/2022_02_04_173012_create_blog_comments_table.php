<?php

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('blog_comments', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Blog::class, 'blog_id')->constrained('blogs');
            $table->foreignIdFor(User::class, 'writer_id')->constrained('users');
            $table->text('content');
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
        Schema::dropIfExists('blog_comments');
    }
}
