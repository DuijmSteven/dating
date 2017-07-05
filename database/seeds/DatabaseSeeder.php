<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTablesSeeder::class);
        $this->call(ConversationsMessagesFlirtsSeeder::class);
        $this->call(ArticlesSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ModuleInstancesSeeder::class);
        $this->call(ActivitySeeder::class);

        Model::reguard();
    }
}
