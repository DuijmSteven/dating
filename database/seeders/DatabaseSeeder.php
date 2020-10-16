<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

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

        $this->call(BotCategoriesSeeder::class);
        $this->call(UserTablesSeeder::class);
        $this->call(ConversationsMessagesFlirtsSeeder::class);
        $this->call(ArticlesSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ModuleInstancesSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(TestimonialsSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(TacSeeder::class);

        Model::reguard();
    }
}
