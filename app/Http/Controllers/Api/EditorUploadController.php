<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EditorUploadController extends Controller
{
    public function store(Request $request)
    {
        $files = $this->flattenFiles($request->allFiles());
        if (empty($files)) {
            return response()->json(['errorMessage' => 'Tidak ada file yang diupload.'], 422);
        }

        $results = [];
        foreach ($files as $file) {
            $validator = Validator::make(['file' => $file], [
                'file' => ['required', 'image', 'max:4096'],
            ]);
            if ($validator->fails()) {
                return response()->json(['errorMessage' => 'File tidak valid.'], 422);
            }

            $results[] = $this->storeImage($file);
        }

        return response()->json([
            'result' => $results,
        ]);
    }

    private function storeImage($file): array
    {
        $dir = public_path('uploads/editor');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $filename = uniqid('editor_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        $path = 'uploads/editor/' . $filename;

        return [
            'url' => url($path),
            'name' => $originalName,
            'size' => $fileSize,
        ];
    }

    private function flattenFiles(array $files): array
    {
        $flat = [];
        foreach ($files as $file) {
            if (is_array($file)) {
                $flat = array_merge($flat, $this->flattenFiles($file));
            } else {
                $flat[] = $file;
            }
        }
        return $flat;
    }
}
