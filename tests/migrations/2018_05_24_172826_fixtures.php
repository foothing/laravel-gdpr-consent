<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fixtures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('gdpr_treatment')->insert([
            'id' => '1',
            'active' => 1,
            'required' => 1,
            'name' => 'privacy_policy',
        ]);

        \DB::table('gdpr_consent')->insert([
            'treatment_id' => 1,
            'subject_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('gdpr_event')->delete();
        \DB::table('gdpr_consent')->delete();
        \DB::table('gdpr_treatment')->delete();
    }
}
