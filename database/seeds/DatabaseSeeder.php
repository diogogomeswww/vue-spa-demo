<?php

use App\Models\Field;
use App\Models\Subscriber;
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
        $this->truncate();
        $this->seed();
    }

    /**
     * @void
     */
    protected function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['users', 'subscribers', 'fields', 'field_subscriber'] as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @void
     */
    protected function seed()
    {
        // Just one demo user
        factory(App\User::class)->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password')
        ]);

        // 3 Fields
        $fields = [
            'Company' => Field::TYPE_STRING,
            'Birthday' => Field::TYPE_DATE,
            'Phone' => Field::TYPE_STRING,
        ];

        foreach ($fields as $title => $type) {
            $fields[$title] = factory(Field::class)->create(compact('title', 'type'));
        }

        // 5 Subscribers
        /** @var \App\Models\Subscriber $user */
        $subscriber = factory(Subscriber::class, 5)->create()->first();

        // attach 3 fields to the first user
        $subscriber->addField($fields['Company'], 'MailerLite');
        $subscriber->addField($fields['Birthday'], '01/01/2000');
        $subscriber->addField($fields['Phone'], '123456789');
    }
}
