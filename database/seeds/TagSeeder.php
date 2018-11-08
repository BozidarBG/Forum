<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags=['Angular', 'Bootstrap', 'HTML & CSS', 'C#', 'Java','PHP', 'Laravel', 'JavaScript',
        'React.JS', 'Foundation', 'GraphQL', 'Vue.JS', 'Spring', 'QA','REST', 'Ruby', 'Ruby On Rails', 'Django', 'Python', 'C++', 'Swift', 'React Native'];
        foreach($tags as $tag){
            $newTag= new \App\Tag();
            $newTag->title=$tag;
            $newTag->body=$tag.' is lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores quos unde voluptate? Asperiores blanditiis dolores eius excepturi fugiat, impedit ipsa magni nostrum nulla optio, praesentium quos sit totam, unde voluptates.';
            $newTag->save();
        }
    }
}
