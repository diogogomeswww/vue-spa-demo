<?php

use Illuminate\Database\Seeder;

class SubscribersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(App\Models\Subscriber::class, 5)->create();
    }
}
