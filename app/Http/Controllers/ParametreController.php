<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        return response()->json([
            'app_name' => config('app.name'),
            'app_env' => config('app.env')
        ]);
    }

    public function update(Request $request)
    {
        // غالباً كيتدار ف database مشي config مباشرة
        return response()->json([
            'message' => 'Paramètres mis à jour'
        ]);
    }
}