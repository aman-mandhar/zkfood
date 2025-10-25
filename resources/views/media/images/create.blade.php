@extends('layouts.front.layout')
@section('content')

<form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="imageable_type" value="{{ $modelClass }}">
    <input type="hidden" name="imageable_id" value="{{ $id }}">

    <div class="mb-3">
        <label for="path">Upload Image</label>
        <input type="file" name="path" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="caption">Caption (optional)</label>
        <input type="text" name="caption" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>

<hr class="my-4">

<h4>Uploaded Images</h4>

@if(is_null($images))
    <p class="text-muted">No images found.</p>

@elseif($images->isEmpty())
    <p class="text-muted">No images uploaded yet.</p>
@else
    <div class="row">
        @foreach($images as $img)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset('storage/' . $img->path) }}" class="card-img-top" alt="Image">

                    <div class="card-body">
                        @if($img->caption)
                            <p class="card-text">{{ $img->caption }}</p>
                        @endif

                        {{-- Delete Button --}}
                        <form action="{{ route('images.destroy', $img->id) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mt-2">🗑️ Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection

