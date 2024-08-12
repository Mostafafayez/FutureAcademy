<?php
namespace App\Http\Controllers;

use App\Models\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PDFController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'pdf' => 'required|mimes:pdf|max:102400', // 10 MB max size
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('pdf')) {
            $fileName = $request->file('pdf')->store('pdfs', 'public');
        } else {
            return response()->json(['errors' => ['pdf' => 'PDF file is required']], 422);
        }

        $pdf = PDF::create([
            'title' => $request->title,
            'description' => $request->description,
            'pdf' => $fileName,
            'lesson_id' => $request->lesson_id,
        ]);

        return response()->json(['message' => 'PDF created successfully', 'pdf' => $pdf], 201);
    }

    public function getByLessonId($lessonId)
    {
        $pdfs = PDF::where('lesson_id', $lessonId)->get();

        if ($pdfs->isEmpty()) {
            return response()->json(['message' => 'No PDFs found for this lesson.'], 404);
        }

        // Since 'FullSrc' is already appended to the model, you don't need to manually add it
        return response()->json(['pdfs' => $pdfs], 200);
    }

    public function destroy($id)
    {
        $pdf = PDF::find($id);

        if (!$pdf) {
            return response()->json(['message' => 'PDF not found.'], 404);
        }

        $pdf->delete();

        return response()->json(['message' => 'PDF deleted successfully.'], 200);
    }
}
