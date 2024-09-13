<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // API to get all images
    public function index()
    {
        $images = Image::all();
        return response()->json($images, 200);
    }

    // API to upload a new image
    public function store(Request $request)
    {
        // Validate the image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the image file
        $imagePath = $request->file('image')->store('images', 'public');

        // Save the image in the database
        $image = Image::create([
            'image' => $imagePath,
        ]);

        return response()->json($image, 201);
    }
}
