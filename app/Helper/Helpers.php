<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('calculate_funding_ratio')) {
    function calculate_funding_ratio($funded, $requested)
    {
        return $requested > 0 ? round($funded / $requested, 2) : 0;
    }
}

if (!function_exists('parse_csv')) {
    function parse_csv($path)
    {
        return array_map('str_getcsv', file($path));
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file)
    {
        try {
            if (is_file($file)) {
                $fileName = time() . "_statement_" . Str::random(3) . '.' . $file->getClientOriginalExtension();
                $path = "uploads/" . $fileName;
                Storage::disk('public')->putFileAs('uploads', $file, $fileName);
                return Storage::url($path);
            }

            throw new Exception('The uploaded file is not valid');
        } catch (\Throwable $th) {
            Log::error("File upload failed", [$th->getMessage()]);
            return false;
        }
    }
}
