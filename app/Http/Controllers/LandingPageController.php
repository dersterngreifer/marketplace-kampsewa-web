<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function halamanBeranda(){
        return view('landing-page.index');
    }

    public function halaman_destinasi() {
        return view('landing-page.destinasi');
    }
    public function halaman_sewabarang() {
        return view('landing-page.sewaBarang');
    }
    public function halaman_testimoni() {
        return view('landing-page.testimoni');
    }
    public function halaman_tentangkami() {
        return view('landing-page.tentangkami');
    }


}
