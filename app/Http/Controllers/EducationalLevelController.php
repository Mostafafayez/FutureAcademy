<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalLevel;

class EducationalLevelController extends Controller
{
    /**
     * Get an educational level by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the educational level by ID
        $educationalLevel = EducationalLevel::find($id);

        // Check if the educational level was found
        if (!$educationalLevel) {
            return response()->json([
                'error' => 'Educational level not found.'
            ], 404);
        }

        // Return the educational level
        return response()->json([
            'educational_level' => $educationalLevel
        ], 200);
    }

    public function getall(){
$eductional = EducationalLevel::all();
return response() ->json(['data' => $eductional], 404 );

    }





    public function showw()
    {
        // Find the educational level by ID
        $educationalLevel = EducationalLevel::get();

        // Check if the educational level was found
        if (!$educationalLevel) {
            return response()->json([
                'error' => 'Educational level not found.'
            ], 404);
        }

        // Return the educational level
        return response()->json([
            'educational_level' => $educationalLevel
        ], 200);
    }
}
