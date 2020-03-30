<?php

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $fields = [
            'Company' => Field::TYPE_STRING,
            'Birthday' => Field::TYPE_DATE,
            'Phone' => Field::TYPE_STRING,
        ];

        foreach($fields as $title => $type) {
            factory(Field::class)->create(compact('title', 'type'));
        }
    }
}
