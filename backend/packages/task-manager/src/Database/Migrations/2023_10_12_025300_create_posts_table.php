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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->softDeletes();

            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            // $table->string('code')->nullable();
            $table->string('tag')->nullable(); //primary or default tag of the post
            $table->string('title');
            $table->string('cover_image_name')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('summary')->nullable();
            $table->string('slug');

            //TEXT: 65,535 characters - 64 KB
            //MEDIUMTEXT: 16,777,215 characters - 16 MB
            //LONGTEXT: 4,294,967,295 characters - 4 GB

            $table->unsignedBigInteger('author_id')->nullable();
            $table->json('content')->nullable();
            $table->json('tags')->nullable(); //->default('[]');
            $table->json('placement')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedTinyInteger('stars')->default(0); //max value 255 - https://dev.mysql.com/doc/refman/8.0/en/integer-types.html
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
