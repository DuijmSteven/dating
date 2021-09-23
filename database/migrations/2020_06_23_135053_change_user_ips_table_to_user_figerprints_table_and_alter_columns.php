<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserIpsTableToUserFigerprintsTableAndAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('user_ips', 'user_fingerprints');

        Schema::table('user_fingerprints', function (Blueprint $table) {
            $table->renameColumn('ip', 'fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('user_fingerprints', 'user_ips');

        Schema::table('user_ips', function (Blueprint $table) {
            $table->renameColumn('fingerprint', 'ip');
        });
    }
}
