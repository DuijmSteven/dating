<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ConversationsMessagesFlirtsSeeder extends Seeder
{
    public function __construct(Faker $faker) {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('conversations')->truncate();
        DB::table('flirts')->truncate();
        DB::table('conversation_messages')->truncate();

        $botIds = User::whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->pluck('id')->toArray();

        $peasantIds = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->pluck('id')->toArray();

        /* -- Add convos, messages and flirts for all peasants and admins -- */
        foreach (array_merge([1], $peasantIds) as $realUserId) {
            $tempBotIds = $botIds;
            $botIdKey = array_rand($tempBotIds);
            $botId = $tempBotIds[$botIdKey];
            unset($tempBotIds[$botIdKey]);

            $conversationAmount = 1;

            for ($i = 0; $i < $conversationAmount; $i++) {
                $conversation = new \App\Conversation([
                    'user_a_id' => $realUserId,
                    'user_b_id' => $botId
                ]);

                $conversation->save();

                $userToBotMessage = new \App\ConversationMessage([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $realUserId,
                    'recipient_id' => $botId,
                    'body' => $this->faker->text(200),
                ]);

                $conversation->messages()->save($userToBotMessage);

                $conversationHasOneMessage = rand(0, 1);

                if (!$conversationHasOneMessage) {
                    $conversationIsNew = rand(0, 1);

                    if (!$conversationIsNew) {
                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'sender_id' => $botId,
                            'recipient_id' => $realUserId,
                            'body' => $this->faker->text(200),
                        ]);

                        $conversation->messages()->save($userToBotMessage);

                    } else {
                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'sender_id' => $realUserId,
                            'recipient_id' => $botId,
                            'body' => $this->faker->text(200),
                        ]);

                        $conversation->messages()->save($userToBotMessage);
                    }
                }

            }

            $flirt = new \App\Flirt([
                'sender_id' => $botId
            ]);

            User::find($realUserId)->receivedFlirts()->save($flirt);
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}