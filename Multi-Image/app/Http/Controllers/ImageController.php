<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    //
    public function index(): View
    {
        return view('imageUpload');
    }

    public function store(Request $request): RedirectResponse
{
    // Validate request
    $request->validate([
        'images' => 'required',
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    
    // Process uploaded images
    $images = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imageName = time() . rand(1, 99) . '.' . $image->extension();  
            $path = $image->storeAs('images', $imageName, 'public');
            
            // Add image to the array
            $images[] = ['name' => $imageName];
        }
    }

    // Save image details to the database
    foreach ($images as $image) {
        Image::create($image);
    }
    
    return back()
            ->with('success', 'You have successfully uploaded images.')
            ->with('images', $images); 
}
}
