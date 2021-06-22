<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        $user1 = User::factory()->create([
            'email' => 'jhon@doe.com'
        ]);

        $user2 = User::factory()->create([
            'email' => 'jane@doe.com'
        ]);


        $channel1 = Channel::factory()->create([
            'user_id' => $user1->id
        ]);

        $channel2 = Channel::factory()->create([
            'user_id' => $user2->id
        ]);

        $channel1->subscriptions()->create([
            'user_id' => $user2->id
        ]);

        $channel2->subscriptions()->create([
            'user_id' => $user1->id
        ]);

        Subscription::factory(10)->create([
            'channel_id' => $channel1->id
        ]);

        Subscription::factory(10)->create([
            'channel_id' => $channel2->id
        ]);

        $video = Video::factory()->create([
            'channel_id' => $channel1->id
        ]);

        Comment::factory(5)->create([
            'video_id' => $video->id
        ]);

        $comment = Comment::first();
        
        //making comment replies for the first comment
        Comment::factory(5)->create([
            'video_id' => $video->id,
            'comment_id' => $comment->id
        ]);

    }
}
