<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EducationalLevel;
class SubjectController extends Controller
{
    /**
     * Get all subjects for a specific educational level ID.
     *
     * @param  int  $educationalLevelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByEducationalLevel($educationalLevelId)
    {



    
        // Retrieve subjects for the specified educational level ID
        $subjects = Subject::where('educational_level_id', $educationalLevelId)->get();

        return response()->json(['subjects' => $subjects], 200);
    }



    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'educational_level' => 'required|string|exists:educational_levels,name',
            'type' => 'required|in:first term,second term',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve the educational level ID based on the name
        $educationalLevel = EducationalLevel::where('name', $request->educational_level)->first();

        // Create the new subject
        $subject = Subject::create([
            'name' => $request->name,
            'educational_level_id' => $educationalLevel->id,
            'type'  => $request->type,
        ]);

        return response()->json(['message' => 'Subject created successfully','subject' => $subject], 201);
    }





    public function destroy($id)
    {
        // Find the subject by ID
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found.'], 404);
        }

        // Delete the subject
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully.'], 200);
    }
}


