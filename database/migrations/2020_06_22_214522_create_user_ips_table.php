<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('ip', 1000);
            $table->timestamps();
        });

        $peasants = \App\User::with(['meta'])
        ->whereHas('roles', function ($query) {
            $query->where('id', \App\User::TYPE_PEASANT);
        })
        ->whereHas('meta', function ($query) {
            $query->where('registration_ip', '!=', null);
        })
        ->get();

        foreach ($peasants as $peasant) {
            $userIpInstance = new \App\UserIp();
            $userIpInstance->setUserId($peasant->getId());
            $userIpInstance->setIp($peasant->meta->getRegistrationIp());
            $userIpInstance->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ips');
    }
}
