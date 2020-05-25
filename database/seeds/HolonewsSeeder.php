<?php

use Illuminate\Database\Seeder;

class HolonewsSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->faker->addProvider(new HydrefLab\JediFaker\Provider\Character($this->faker));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = factory(\Modules\Holonews\Models\Author::class)->create([
            "name" => $this->faker->lightSide,
            "email" => "vador@sith.gal",
        ]);

        $articles = factory(\Modules\Holonews\Models\Article::class)->times(10)->create([
            "author_id" => $author->id,
        ]);

        $tags = factory(\Modules\Holonews\Models\Tag::class)->times(2)->create();

        foreach ($tags as $tag) {
            $articles->first()->tags()->attach($tag);
        }
    }
}
