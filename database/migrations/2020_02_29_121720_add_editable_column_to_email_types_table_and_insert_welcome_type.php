<?php

use App\EmailType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditableColumnToEmailTypesTableAndInsertWelcomeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_types', function (Blueprint $table) {
            $table->boolean('editable')->default(false);
        });

        EmailType::query()->update(['editable' => true]);

        EmailType::insert([
            [
                'name' => 'welcome',
                'description' => 'This email is sent when a user registers',
            ],
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
            $table->dropColumn('editable');
        });
    }
}
