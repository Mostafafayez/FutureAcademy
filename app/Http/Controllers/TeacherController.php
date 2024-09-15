<?php

namespace App\Http\Controllers;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EducationalLevel;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
class TeacherController extends Controller
{




    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
           'description' =>'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'educational_level' => 'required|string|exists:educational_levels,name',
            'subject' => 'required|string|exists:subjects,name'
        ]);


        $user=Auth::user();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('images', 'public');
        } else {
            $fileName = null;
        }


        $educationalLevel = EducationalLevel::where('name', $request->educational_level)->first();
        $subject = Subject::where('name', $request->subject)->first();


        $teacher = teacher::create([
            'name' => $request->name,
            'description' =>    $request->  description,
            'image' => $fileName,
            'educational_level_id' => $educationalLevel->id,
            'subject_id' => $subject->id,
        ]);

        return response()->json(['message' => 'Teacher created successfully', 'teacher' => $teacher], 201);
    }


    public function index()
    {
        $teachers = Teacher::with(['subject', 'educationalLevel'])->get();

        // Map over the collection to format the response as needed
        $teachers = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,

                'educational_level' => $teacher->educationalLevel,
                'subject' => $teacher->subject,
                'image' => $teacher->FullSrc
            ];
        });

        return response()->json(['teachers' => $teachers], 200);
    }



    public function getall()
    {
        $teachers = Teacher::all();

        // Map over the collection to format the response as needed
        return response()->json(['teachers' => $teachers], 200);
    }


    public function search(Request $request)
    {
        // Check if there's a 'name' query parameter in the request
        $name = $request->input('name');

        // If a name is provided, filter by name; otherwise, get all teachers
        if ($name) {
            $teachers = Teacher::where('name', 'like', '%' . $name . '%')->get();
        } else {
        return[ 'no teacher found'];
        }

        // Return the response with the teachers data
        return response()->json(['teachers' => $teachers], 200);
    }



    public function show($id)
    {
        $teacher = Teacher::with(['subject', 'educationalLevel'])->find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        }

        $teacherData = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'educational_level' => $teacher->educationalLevel,
            'subject' => $teacher->subject,
            'description' =>$teacher->description,
            // 'FullSrc' => url('storage/' . $teacher->image),
            'image' => $teacher->FullSrc
        ];

        return response()->json(['teacher' => $teacherData], 200);
    }


    public function getTeachersByEducationalLevel($educationalLevelId)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch teachers based on educational level
        $teachers = teacher::where('educational_level_id', $educationalLevelId)
            ->with(['subject', 'educationalLevel'])
            ->get();

        // Check if any teachers were found
        if ($teachers->isEmpty()) {
            return response()->json(['message' => 'No teachers found for this educational level.'], 404);
        }

        $teachersData = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'educational_level' => $teacher->educationalLevel ? $teacher->educationalLevel->name : 'N/A',  // Check if educationalLevel exists
                'subject' => $teacher->subject ? $teacher->subject->name : 'N/A',  // Check if subject exists
                'FullSrc' => url('storage/' . $teacher->image)
            ];
        });

        return response()->json(['teachers' => $teachersData], 200);
    }



    public function destroy($id)
    {
        $teacher = teacher::find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        }

        $teacher->delete();

        return response()->json(['message' => 'Teacher deleted successfully'], 200);
    }


    public function getTeaddchers()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch teachers based on educational level
        $teachers = teacher:: with(['subject', 'educationalLevel'])
            ->get();

        // Check if any teachers were found
        if ($teachers->isEmpty()) {
            return response()->json(['message' => 'No teachers found for this educational level.'], 404);
        }
        $teachersData = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'educational_level' => $teacher->educationalLevel ? $teacher->educationalLevel->name : 'N/A',  // Check if educationalLevel exists
                'subject' => $teacher->subject ? $teacher->subject->name : 'N/A',  // Check if subject exists
                'FullSrc' => url('storage/' . $teacher->image)
            ];
        });

        return response()->json(['teachers' => $teachersData], 200);
}
}
