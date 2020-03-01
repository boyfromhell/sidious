<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('posts_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');

            $table->unique(['post_id', 'tag_id']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt');
            $table->text('body');
            $table->boolean('published')->default(false);
            $table->dateTime('publish_date')->default('2018-10-10 00:00:00');
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption');
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('bio');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->text('meta')->nullable();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts_tags');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('pages');
    }
}
