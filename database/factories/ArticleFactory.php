<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Modules\Holonews\Models\Article::class, function (Faker $faker) {
    static $author_id;
    static $title;

    return [
        "title" => ($title) ? $title : $faker->sentence,
        "author_id" => ($author_id) ? $author_id : factory(\Modules\Holonews\Models\Author::class)->create()->id,
        "body" => $faker->sentence,
        "excerpt" => $faker->sentence,
        "publish_date" => new DateTime(),
        "published" => true,
    ];
});
