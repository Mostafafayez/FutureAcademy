<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // Get all offers
    public function index()
    {
        return response()->json([
            'status' => true,
            'offers' => Offer::with('educationalLevel')->get()
        ]);
    }

    // Get offers by educational level id
    public function getByEducationalLevel($educational_level_id)
    {
        return response()->json([
            'status' => true,
            'offers' => Offer::where('educational_level_id', $educational_level_id)
                              ->with('educationalLevel')
                              ->get()
        ]);
    }

    // Add new offer
    public function store(Request $request)
    {
        $request->validate([
            'educational_level_id' => 'required|exists:educational_levels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|integer|min:0|max:100',
        ]);

        $offer = Offer::create($request->all());

        return response()->json([
            'status' => true,
            'offer' => $offer
        ], 201);
    }

    // Delete offer
    public function destroy($id)
    {
        $offer = Offer::find($id);

        if (!$offer) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        $offer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully'
        ]);
    }
}
