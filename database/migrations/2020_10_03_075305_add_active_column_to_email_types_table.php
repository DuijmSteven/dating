<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToEmailTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_types', function (Blueprint $table) {
            $table->boolean('active')
                ->default(true);
        });

        \App\EmailType::where('id', 2)
            ->update([
                'active' => false
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_types', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
