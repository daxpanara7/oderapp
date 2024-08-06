<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function showThankYouPage(Request $request)
    {
        $redirectUrl = $request->input('redirectUrl', '/order');
        $jsonResponse = $request->input('jsonResponse', '{}');

        return view('thankyou', compact('redirectUrl', 'jsonResponse'));
    }
}
