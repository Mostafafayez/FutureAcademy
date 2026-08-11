<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\packages;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
{public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'subject' => 'required|string|exists:subjects,name',
            'teacher_id' => 'required|exists:teachers,id',  // Validate teacher_id
            'educational_id' =>'required|exists:educational_levels,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve the subject ID based on the name
        $subject = Subject::where('name', $request->subject)->first();

        if (!$subject) {
            return response()->json(['message' => 'Subject not found.'], 404);
        }

        $package = packages::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $subject->id,
            'teacher_id' => $request->teacher_id,// Add teacher_id
            'educational_level_id' => $request->educational_id,
        ]);



           if ($request->hasFile('image')) {
        $fileName = $request->file('image')->store('images/packages', 'public');

        $package->image()->create([
            'image_url' => $fileName
        ]);
    }

        return response()->json(['message' => 'packages created successfully', 'packages' => $package], 201);
    }







    public function getpackagesByteacherId($teacherId, $educationalLevel)
    {

        $lessons = packages::where('teacher_id', $teacherId)
            ->where('educational_level_id', $educationalLevel) // Adjust according to your column name
            ->with(['educationalLevel','image']) // Eager load relationships
            ->get();

        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No packages found for this teacher at the specified educational level.'], 404);
        }

        return response()->json(['packages' => $lessons], 200);
    }


    public function destroy($id)
    {
        $Lesson = packages::find($id);

        if (!$Lesson) {
            return response()->json(['message' => 'packages not found.'], 404);
        }

        $Lesson->delete();

        return response()->json(['message' => 'packages deleted successfully'], 200);
    }


    
public function updatePartial(Request $request, $id)
{
    // Validate only the fields that are provided
    $validated = $request->validate([
        'id' => 'sometimes|integer|unique:packages,id,' . $id,
        'subject_id' => 'sometimes|integer|exists:subjects,id',
        'teacher_id' => 'sometimes|integer|exists:teachers,id',
        'educational_level_id' => 'sometimes|integer|exists:educational_levels,id',
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|nullable|string',
    ]);

    // Find package using the current ID
    $package = packages::find($id);

    if (!$package) {
        return response()->json([
            'status' => 400,
            'message' => 'Package not found',
        ], 400);
    }

    // Update the provided fields
    if ($request->has('id')) {
        $package->id = $request->id;
    }

    if ($request->has('subject_id')) {
        $package->subject_id = $request->subject_id;
    }

    if ($request->has('teacher_id')) {
        $package->teacher_id = $request->teacher_id;
    }

    if ($request->has('educational_level_id')) {
        $package->educational_level_id = $request->educational_level_id;
    }

    if ($request->has('title')) {
        $package->title = $request->title;
    }

    if ($request->has('description')) {
        $package->description = $request->description;
    }

    $package->save();

    return response()->json([
        'status' => 200,
        'message' => 'Package updated successfully',
        'data' => $package,
    ], 200);
}



}
