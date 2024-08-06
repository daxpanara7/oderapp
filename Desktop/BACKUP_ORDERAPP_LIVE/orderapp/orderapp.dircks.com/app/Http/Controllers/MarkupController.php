<?php

namespace App\Http\Controllers;

use App\Quotation;
use App\Markup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyEmail;

class MarkupController extends Controller
{
    // Display the list of markups
    public function index()
    {
        $markups = Markup::all();
        return view('markups.index', compact('markups'));
    }

    // Store a new markup
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'type' => 'required|string|max:255',
            'value' => 'required|numeric',
        ]);

        // Check for existing markup
        $existingMarkup = Markup::where('type', $request->type)->first();
        if ($existingMarkup) {
            return response()->json(['message' => 'Markup already exists'], 409); // 409 Conflict
        }

        // Create new markup
        $markup = Markup::create([
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return response()->json($markup);
    }

    // Show a single markup
    public function show($id)
    {
        $markup = Markup::findOrFail($id);
        return response()->json($markup);
    }

    // Update an existing markup
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'type' => 'required|string|max:255',
            'value' => 'required|numeric',
        ]);

        // Find the markup
        $markup = Markup::findOrFail($id);

        // Update the markup
        $markup->update([
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return response()->json($markup);
    }

    // Delete a markup
    public function destroy($id)
    {
        $markup = Markup::findOrFail($id);
        $markup->delete();

        return response()->json(['success' => true]);
    }
}