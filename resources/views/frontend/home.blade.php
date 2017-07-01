@extends('frontend.layouts.default.layout')

@section('content')

    @php
        $faker = new \Faker\Generator();
        $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
        $faker->addProvider(new \Faker\Provider\Image($faker));
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\DateTime($faker));
    @endphp

    @for($i = 0; $i < 3; $i++)
        @include('frontend.components.activity', [
            'activityThumbUrl' => $faker->imageUrl(),
            'activityTitle' => $faker->text(30),
            'activityDate' => $faker->dayOfWeek . ' ' . $faker->dayOfMonth . 'th ' . $faker->year . ', ' . $faker->time('H:i'),
            'activityImageUrl' => $faker->imageUrl(),
            'activityText' => $faker->realText(120),
        ])
    @endfor

@endsection
