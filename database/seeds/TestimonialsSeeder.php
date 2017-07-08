<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class TestimonialsSeeder
 */
class TestimonialsSeeder extends Seeder
{
    /**
     * TestimonialsSeeder constructor.
     * @param Faker $faker
     */
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
        DB::table('testimonials')->truncate();
        DB::table('testimonial_users')->truncate();

        // create uncoupled
        factory(App\Testimonial::class, 10)->create()->each(function ($testimonial) {
            $testimonial->users()->save(factory(App\TestimonialUser::class)->make());
        });

        // create coupled
        factory(App\Testimonial::class, 10)->create()->each(function ($testimonial) {
            /** @var \App\TestimonialUser $firstUser */
            $firstUser = $testimonial->users()->save(factory(App\TestimonialUser::class)->make());
            /** @var \App\TestimonialUser $secondUser */
            $secondUser = $testimonial->users()->save(factory(App\TestimonialUser::class)->make());
        });

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}