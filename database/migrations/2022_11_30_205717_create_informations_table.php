<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informations', function (Blueprint $table) {
            $table->id();
            $table->string('expert_name');
            $table->text('experiences')->nullable();
            $table->text('image_url')->nullable();
            $table->text('contact_info')->nullable();
            $table->enum('consultation_type',
            ['Medical', 'Professional','Psychological','Family','Management']
            )->nullable();
            $table->timestamps();
            $table->foreignId('expert_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informations');
    }
}
