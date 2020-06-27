<?php

use App\UserMeta;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEmailVerificationStatusColumnFromUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropColumn('email_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->smallInteger('email_verification_status')
                ->unsigned()
                ->nullable()
                ->default(null);
        });
    }
}
