<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoLinkController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->type; // e.g., "sikh"
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
        

        return view('media.videos.create', [
            'modelClass' => $modelClass,
            'id' => $id,
            'videos' => $modelInstance->videoLinks, // 🧠 send video links
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'videoable_type' => 'required|string',
            'videoable_id'   => 'required|integer',
            'link'           => ['required', 'url', 'regex:/^(https:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/'],
            'caption'        => 'nullable|string|max:255',
        ]);

        \App\Models\VideoLink::create([
            'user_id'        => auth()->id(),
            'videoable_type' => $request->videoable_type,
            'videoable_id'   => $request->videoable_id,
            'link'           => $request->link,
            'caption'        => $request->caption,
        ]);

        return redirect()->back()->with('success', 'Video link added successfully!');
    }

    public function destroy($id)
    {
        $video = \App\Models\VideoLink::findOrFail($id);

        // Optional: check ownership
        if ($video->user_id !== auth()->id()) {
            abort(403); // unauthorized access
        }

        $video->delete();

        return back()->with('success', 'Video deleted successfully!');
    }

}
