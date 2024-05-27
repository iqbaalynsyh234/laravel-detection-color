<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ObjectDetectionController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'camera-image' => 'nullable|string',
        ]);

        $imageName = 'logam_' . now()->format('Ymd_His') . '.png';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->storeAs('uploads', $imageName, 'public');
        } elseif ($request->input('camera-image')) {
            $cameraImage = $request->input('camera-image');
            $cameraImage = str_replace('data:image/png;base64,', '', $cameraImage);
            $cameraImage = str_replace(' ', '+', $cameraImage);
            $imageData = base64_decode($cameraImage);

            Storage::disk('public')->put('uploads/' . $imageName, $imageData);
            $path = 'uploads/' . $imageName;
        } else {
            return back()->withErrors(['error' => 'No image uploaded']);
        }

        $fullPath = storage_path('app/public/' . $path);
        $referenceImagePath = storage_path('app/public/uploads/warna.jpeg'); // Update this path accordingly

        $command = "python " . base_path('detect_and_compare.py') . " " . escapeshellarg($fullPath) . " " . escapeshellarg($referenceImagePath);
        $output = shell_exec($command);
        
        // Log the output for debugging
        Log::info('Python script output: ' . $output);

        // Split the output and check the contents
        $outputFiles = explode(',', trim($output));

        // Check if the expected files are generated
        if (count($outputFiles) < 3) {
            return back()->withErrors(['error' => 'Python script did not produce the expected output.']);
        }

        // Generate timestamp for filenames
        $timestamp = now()->format('Ymd_His');

        // Save the output files to storage/app/public
        $csvPath = Storage::put("public/uploads/matching_colors_$timestamp.csv", file_get_contents($outputFiles[0]));
        $chartPath = Storage::put("public/uploads/matching_color_distances_$timestamp.png", file_get_contents($outputFiles[1]));
        $pdfPath = Storage::put("public/uploads/matching_color_distances_$timestamp.pdf", file_get_contents($outputFiles[2]));

        return view('results', [
            'originalImagePath' => asset('storage/' . $path),
            'annotatedImagePath' => asset('storage/' . basename($outputFiles[1])),
            'chartImagePath' => asset('storage/uploads/matching_color_distances_' . $timestamp . '.png'),
            'excelPath' => asset($csvPath),
            'pdfPath' => asset($pdfPath)
        ]);
    }
}