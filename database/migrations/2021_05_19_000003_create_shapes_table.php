<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Shapes table creation.
 *
 * @since 1.0.0
 */
class CreateShapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @since 1.0.0
     */
    public function up()
    {
        Schema::create('shapes', function (Blueprint $table) {
            $table->id();
            $table->char('name');
            $table->text('description');
            $table->json('coordinates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @since 1.0.0
     */
    public function down()
    {
        Schema::dropIfExists('shapes');
    }
}
