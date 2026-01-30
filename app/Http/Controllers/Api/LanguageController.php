<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function getLang($code)
    {
        $path = resource_path('lang/'.$code.'.json');
        $langData = json_decode(file_get_contents($path), true);

        return $langData;
    }
}
