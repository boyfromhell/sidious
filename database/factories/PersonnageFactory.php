<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Modules\Personnages\Models\Personnage::class, function (Faker $faker) {
    static $name;
    static $owner_id;

    return [
        'name' => ($name) ? $name : $faker->name,
        'owner_id' => ($owner_id) ? $owner_id : factory(\App\User::class)->create()->id,
    ];
});
