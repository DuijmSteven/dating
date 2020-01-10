<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingPageController extends FrontendController
{
    public function show(Request $request)
    {
        $locale = 'nl';

        $localeFromRequest = $request->get('locale');

        if ($localeFromRequest && in_array($localeFromRequest, ['nl', 'en'])) {
            $locale = $request->get('locale');
        }

        app()->setLocale($locale);

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'peasant')
                ->orWhere('name', 'bot');
        })
            ->inRandomOrder()->take(6)->get();

        $testimonials = [
            [
                'quote' => 'Ik weet niet of ik zonder deze website ook zo snel contact gehad zou hebben. 
                            Erg makkelijk in gebruik, leuke contacten en na een paar weken had ik de eerste date! Super bedankt.',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Timo, 28'
            ],
            [
                'quote' => 'Als ‘mooie’ vrouw krijg je vaak eindeloos veel berichten en vriendschapsverzoeken, maar gelukkig dat ik hier wel mensen trof die serieus waren.',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Amber, 24'
            ],
            [
                'quote' => 'Was op zoek naar een leuke date en gevonden...Super geslaagd dus',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Remco, 36'
            ],
            [
                'quote' => 'Ik ben blij dat ik eindelijk een datingsite heb gevonden waar je zonder onderbreking kunt chatten. Niet meer eindeloos herladen of gesprek voor gesprek openen, gewoon lekker kletsen én daten natuurlijk.',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Herman, 53'
            ],
            [
                'quote' => 'Na een spanningsloos huwelijk was ik wel toe aan wat leven in de brouwerij. Hier heb ik een paar leuke vrouwen leren kennen waar ik zo nu en dan eens een bakkie koffie mee kan drinken.',
                'imgSource' => 'https://www.gannett-cdn.com/-mm-/c15f5f19a52dc82a8ae04998311685d79708bb7f/c=0-160-361-521/local/-/media/2017/12/21/INGroup/Indianapolis/636494825523802717-couple-1.png?width=200&height=200&fit=crop',
                'names' => 'Nico, 45'
            ],
            [
                'quote' => 'Zelf had ik niet helemaal meer verwacht van zo’n datingsite gebruik te maken. Ik verbaasde me in het beginsel over het gemak waar je een gesprek mee voert en dat het gaandeweg echt leuker wordt. Uiteindelijk heb ik nog een paar weken gechat, maar nu ben ik gelukkig en hopelijk voorlopig voorzien haha',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Johannes, 49'
            ],
            [
                'quote' => 'Was wel toe aan iets anders dan Tinder en gewoon oprecht een leuk gesprekje voeren voor je elkaar in het echt treft en de hemd van het lijf scheurt.',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Laura, 29'
            ],
            [
                'quote' => 'Op mijn leeftijd sluit je niet meer zo makkelijk aan in de kroeg. Op enkele datingsites kwam ik bedrogen uit, maar ik ben blij dat ik op altijdsex toch nog invulling heb kunnen geven aan mijn lusten, ook al ben ik niet meer de jongste, de oudste zal ik ook zeker niet zijn, toch?',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Mr. Jones, 68'
            ],
        ];

        return view(
            'frontend.landing-pages.1',
            [
                'title' => config('app.name'),
                'users' => $users,
                'carbonNow' => Carbon::now(),
                'testimonials' => $testimonials
            ]
        );
    }
}
