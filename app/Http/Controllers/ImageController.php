<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request, $name)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = $name . time() . '.' . $request->image->getClientOriginalExtension();
        if ($request->image->move(public_path('uploads'), $imageName))
        {
            return $imageName;
        }

        return false;
    }

    public function delete(String $imageName)
    {
        $imagePath = public_path('uploads/' . $imageName);

        if (file_exists($imagePath))
        {
            return unlink($imagePath);;
        }

        return false;
    }
}
