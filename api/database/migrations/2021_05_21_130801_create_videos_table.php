<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id');
            //supposed to be object but i still didnt figure what its attribute yet
            //$table->string('metadata')->nullable();
            // This may change into channel or profile id
            $table->foreignUuid('channel_id')->constrained()->onDelete('cascade');
            $table->bigInteger('views')->default(0);
            $table->string('thumbnail')->nullable();
            $table->integer('percentage')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('path')->nullable();
            
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('videos');
        Schema::enableForeignKeyConstraints();
    }
}
