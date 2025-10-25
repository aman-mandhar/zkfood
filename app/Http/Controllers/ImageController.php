<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        $modelClass = match ($type) {
            'sikh' => \App\Models\SikhDirectory::class,
            'business' => \App\Models\BusinessDirectory::class,
            'metrimonial' => \App\Models\MetrimonialDirectory::class,
            'job' => \App\Models\JobDirectory::class,
            'post' => \App\Models\Post::class,
            default => abort(404),
        };

        $modelInstance = $modelClass::findOrFail($id);
        return view('media.images.create', [
            'modelClass' => $modelClass,
            'id' => $id,
            'images' => $modelInstance->images, // 🔥 fetch images
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'imageable_type' => 'required|string',
            'imageable_id'   => 'required|integer',
            'path'           => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'caption'        => 'nullable|string|max:255',
        ]);

        // Save image file
        $imagePath = $request->file('path')->store('uploads/images', 'public');

        // Create record
        \App\Models\Image::create([
            'user_id'        => auth()->id(),
            'imageable_type' => $request->imageable_type,
            'imageable_id'   => $request->imageable_id,
            'path'           => $imagePath,
            'caption'        => $request->caption,
        ]);

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }

    public function destroy($id)
    {
        $image = \App\Models\Image::findOrFail($id);

        // Check if image file exists and delete it
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }

}
