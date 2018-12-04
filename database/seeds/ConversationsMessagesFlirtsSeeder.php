<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Carbon\Carbon;

/**
 * Class ConversationsMessagesFlirtsSeeder
 */
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
        DB::table('conversation_messages')->truncate();
        DB::table('conversation_notes')->truncate();

        $botIds = User::whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->pluck('id')->toArray();

        $peasantIds = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->pluck('id')->toArray();

        /* -- Add conversations, messages and flirts for all peasants and admins -- */
        foreach ($peasantIds as $realUserId) {
            $tempBotIds = $botIds;
            $botIdKey = array_rand($tempBotIds);
            $botId = $tempBotIds[$botIdKey];
            unset($tempBotIds[$botIdKey]);

            $startDate = Carbon::create(
                rand(Carbon::now()->year - 2, Carbon::now()->year),
                rand(1, Carbon::now()->month),
                rand(1, Carbon::now()->day - 1),
                rand(1, 22),
                rand(1, 59),
                rand(1, 59)
            );

            $conversationAmount = rand(1, 2);

            for ($i = 0; $i < $conversationAmount; $i++) {
                $conversation = new \App\Conversation([
                    'user_a_id' => $realUserId,
                    'user_b_id' => $botId,
                    'created_at' => $startDate->toDateTimeString(),
                    'updated_at' => $startDate->toDateTimeString()
                ]);

                $conversation->save();

                $messageIsFlirt = rand(0, 1);
                $messageType = $messageIsFlirt ? 'flirt' : 'generic';

                $userToBotMessage = new \App\ConversationMessage([
                    'conversation_id' => $conversation->id,
                    'type' => $messageType,
                    'sender_id' => $realUserId,
                    'recipient_id' => $botId,
                    'body' => $messageIsFlirt ? null : $this->faker->text(200),
                    'created_at' => $startDate->toDateTimeString(),
                    'updated_at' => $startDate->toDateTimeString()
                ]);

                $conversation->messages()->save($userToBotMessage);

                $randomCategoryId = [
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8'
                ];

                foreach ([$realUserId, $botId] as $userId) {
                    $categoriesAmount = rand(1, 3);

                    for ($i = 0; $i < $categoriesAmount; $i++) {
                        $category = $this->faker->randomElement($randomCategoryId);

                        $notesAmount = rand(1, 5);

                        for ($j = 0; $j < $notesAmount; $j++) {
                            $conversationNote = new \App\ConversationNote([
                                'conversation_id' => $conversation->id,
                                'category_id' => $category,
                                'user_id' => $userId,
                                'body' => $this->faker->text(rand(15, 250)),
                            ]);

                            $conversationNote->save();
                        }
                    }
                }

                $conversationHasOneMessage = rand(0, 1);

                if (!$conversationHasOneMessage) {
                    $conversationIsNew = rand(0, 1);

                    // determines if message is going to be a flirt
                    $messageIsFlirt = rand(0, 1);

                    $dateTime = $startDate->addMinutes(rand(1, 30))->addSeconds(rand(1, 59))->toDateTimeString();

                    if (!$conversationIsNew) {
                        $messageType = $messageIsFlirt ? 'flirt' : 'generic';

                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'type' => $messageType,
                            'sender_id' => $botId,
                            'recipient_id' => $realUserId,
                            'body' => $this->faker->text(200),
                            'created_at' => $dateTime,
                            'updated_at' => $dateTime
                        ]);

                        $conversation->messages()->save($userToBotMessage);
                    } else {
                        $messageType = $messageIsFlirt ? 'flirt' : 'generic';

                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'type' => $messageType,
                            'sender_id' => $realUserId,
                            'recipient_id' => $botId,
                            'body' => $this->faker->text(200),
                            'created_at' => $dateTime,
                            'updated_at' => $dateTime
                        ]);

                        $conversation->messages()->save($userToBotMessage);
                    }
                }

                $conversationHasTwoMessages = rand(0, 1);

                if (!$conversationHasTwoMessages) {
                    // determines if message is going to be a flirt
                    $messageIsFlirt = rand(0, 1);

                    $dateTime = $startDate->addMinutes(rand(1, 30))->addSeconds(rand(1, 59))->toDateTimeString();

                    $botToUser = rand(0,1);

                    if (!$botToUser) {
                        $messageType = $messageIsFlirt ? 'flirt' : 'generic';

                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'type' => $messageType,
                            'sender_id' => $botId,
                            'recipient_id' => $realUserId,
                            'body' => $this->faker->text(200),
                            'created_at' => $dateTime,
                            'updated_at' => $dateTime
                        ]);

                        $conversation->messages()->save($userToBotMessage);
                    } else {
                        $messageType = $messageIsFlirt ? 'flirt' : 'generic';

                        $userToBotMessage = new \App\ConversationMessage([
                            'conversation_id' => $conversation->id,
                            'type' => $messageType,
                            'sender_id' => $realUserId,
                            'recipient_id' => $botId,
                            'body' => $this->faker->text(200),
                            'created_at' => $dateTime,
                            'updated_at' => $dateTime
                        ]);

                        $conversation->messages()->save($userToBotMessage);
                    }
                }

            }
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}