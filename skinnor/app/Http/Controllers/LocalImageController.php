<?php

namespace App\Http\Controllers;

class LocalImageController extends Controller
{
    public function show($filename)
    {
        $paths = [
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_2',
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1',
        ];

        foreach ($paths as $path) {
            $fullPath = $path.DIRECTORY_SEPARATOR.$filename;
            if (file_exists($fullPath)) {
                return response()->file($fullPath);
            }
        }

        abort(404, 'Imagen no encontrada.');
    }
}
