<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationController extends Controller
{
    public function index($locale){
        App::setlocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
