<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UUIDController extends Controller
{

    public function getUUID(): JsonResponse
    {
        try {
            $process = new Process(['wmic', 'csproduct', 'get', 'uuid']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $output = trim($output);

            // Extract UUID from Windows command output
            $lines = explode("\n", $output);
            $uuid = trim($lines[1] ?? '');

            // Clean up any unwanted characters
            $uuid = preg_replace('/[^a-fA-F0-9-]/', '', $uuid);

            return response()->json(['uuid' => $uuid]);
        } catch (\Exception $e) {
            Log::error('Error fetching UUID: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch UUID'], 500);
        }
    }
}
