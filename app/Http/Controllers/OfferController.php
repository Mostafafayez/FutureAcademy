<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    // Get all offers
public function index()
{
    $offers = Offer::with([
        'teacher.subject', // 👈 يجيب المادة من المدرس
    ])->get();

    return response()->json([
        'status' => true,
        'offers' => $offers
    ]);
}

    // Get offers by educational level id
public function getByEducationalLevel($id)
{
    $offers = Offer::where('educational_level_id', $id)
        ->with('teacher.subject')
        ->get();

    return response()->json([
        'status' => true,
        'offers' => $offers
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

    // 👇 المدرس من التوكن
   $teacher = Auth::guard('teacher')->user();


    $offer = Offer::create([
        'educational_level_id' => $request->educational_level_id,
        'teacher_id' => $teacher->id, // 👈 أهم سطر
        'title' => $request->title,
        'description' => $request->description,
        'discount_percentage' => $request->discount_percentage,
    ]);

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
