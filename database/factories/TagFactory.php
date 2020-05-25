<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Modules\Holonews\Models\Tag::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "color" => $faker->hexColor,
    ];
});
