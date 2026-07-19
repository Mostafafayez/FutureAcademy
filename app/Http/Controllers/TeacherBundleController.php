<?php

namespace App\Http\Controllers;

use App\Models\TeacherBundle;
use Illuminate\Http\Request;

class TeacherBundleController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'educational_level_id' => 'required|exists:educational_levels,id',
        'price' => 'required|numeric|min:0',
        'status' => 'required|boolean',
        'teachers' => 'required|array|min:1',
        'teachers.*' => 'exists:teachers,id',
    ]);

    $bundle = TeacherBundle::create([
        'title' => $request->title,
        'description' => $request->description,
        'educational_level_id' => $request->educational_level_id,
        'price' => $request->price,
        'status' => $request->status,
    ]);

    $bundle->teachers()->sync($request->teachers);

    return response()->json([
        'message' => 'Bundle created successfully',
        'data' => $bundle->load('teachers')
    ]);
}
public function index()
{
    $bundles = TeacherBundle::with([
        'teachers',
        'educationalLevel'
    ])->get();

    return response()->json($bundles);
}

public function show($id)
{
    $bundle = TeacherBundle::with([
        'teachers',
        'educationalLevel'
    ])->findOrFail($id);

    return response()->json($bundle);
}

public function update(Request $request, $id)
{
    $bundle = TeacherBundle::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'educational_level_id' => 'required|exists:educational_levels,id',
        'price' => 'required|numeric|min:0',
        'status' => 'required|boolean',
        'teachers' => 'required|array',
        'teachers.*' => 'exists:teachers,id',
    ]);

    $bundle->update([
        'title' => $request->title,
        'description' => $request->description,
        'educational_level_id' => $request->educational_level_id,
        'price' => $request->price,
        'status' => $request->status,
    ]);

    $bundle->teachers()->sync($request->teachers);

    return response()->json([
        'message' => 'Bundle updated successfully',
        'data' => $bundle->load('teachers')
    ]);
}

public function destroy($id)
{
    $bundle = TeacherBundle::findOrFail($id);

    $bundle->delete();

    return response()->json([
        'message' => 'Bundle deleted successfully'
    ]);
}

public function getByTeacher($teacher_id)
{
    $bundles = TeacherBundle::with([
        'teachers',
        'educationalLevel'
    ])
    ->whereHas('teachers', function ($q) use ($teacher_id) {
        $q->where('teachers.id', $teacher_id);
    })
    ->get();

    return response()->json($bundles);
}

public function getByEducationalLevel($id)
{
    $bundles = TeacherBundle::with([
        'teachers',
        'educationalLevel'
    ])
    ->where('educational_level_id', $id)
    ->get();

    return response()->json($bundles);
}
}
