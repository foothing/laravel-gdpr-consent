<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GdprConsent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdpr_treatment', function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->boolean('active')->default(true);
            $table->boolean('required')->default(true);
            $table->string('name')->unique();
            $table->text('description');
            $table->string('documentVersion')->nullable()->default(null);
            $table->string('documentUrl')->nullable()->default(null);
            $table->tinyInteger('weight');
            $table->timestamps();
        });

        Schema::create('gdpr_consent', function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('treatment_id');
            $table->string('subject_id');
            $table->timestamps();

            $table->unique(['subject_id', 'treatment_id']);

            $table->foreign('treatment_id')->references('id')->on('gdpr_treatment');
        });

        Schema::create('gdpr_event', function(Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('treatment_id')->nullable();
            $table->uuid('consent_id')->nullable();
            $table->string('subject_id');
            $table->string('ip');
            $table->string('action');
            $table->text('payload');
            $table->timestamps();

            $table
                ->foreign('treatment_id')
                ->references('id')
                ->on('gdpr_treatment');

            $table
                ->foreign('consent_id')
                ->references('id')
                ->on('gdpr_consent')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gdpr_event');
        Schema::dropIfExists('gdpr_consent');
        Schema::dropIfExists('gdpr_treatment');
    }
}
