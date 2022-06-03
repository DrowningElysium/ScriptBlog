<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'writer_id')->constrained('users');
            $table->string('title', 255);
            $table->longText('content'); // Might be far too much, at least I know a person won't ever touch the 4GiB.
            // Might store content either also in for example Elasticsearch to make it searchable. However, I think this might currently be too much out of scope.
            $table->boolean('premium')->default(false);
            $table->timestamp('published_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
