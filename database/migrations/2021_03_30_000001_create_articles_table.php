<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Articles table creation.
 *
 * @since 1.0.0
 */
class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->char('author')->nullable();
            $table->mediumText('articleBody')->nullable();
            $table->text('abstract')->nullable();
            $table->char('publisher')->nullable();
            $table->json('aggregateRating')->nullable();
            $table->dateTime('datePublished')->nullable();
            $table->char('thumbnailUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
