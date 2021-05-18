<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Locations table creation.
 *
 * @since 1.0.0
 */
class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @since 1.0.0
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->char('streetAddress')->nullable();
            $table->char('addressLocality')->nullable();
            $table->char('addressRegion')->nullable();
            $table->char('addressCountry')->nullable();
            $table->char('postalCode')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->text('photoUrl')->nullable();
            $table->text('photoDescription')->nullable();
            $table->boolean('sensitive')->default(false);
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
        Schema::dropIfExists('locations');
    }
}
