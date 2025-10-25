@extends('layouts.front.layout')
@section('content')
<form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="videoable_type" value="{{ $modelClass }}">
    <input type="hidden" name="videoable_id" value="{{ $id }}">

    <div class="mb-3">
        <label for="link">Youtube Video Link</label>
        <input type="text" name="link" class="form-control" placeholder="https://www.youtube.com/watch?v=example" required>
    </div>

    <div class="mb-3">
        <label for="caption">Caption (optional)</label>
        <input type="text" name="caption" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<hr class="my-4">
<h4>Uploaded Videos</h4>
@if($videos->isEmpty())
    <p class="text-muted">No videos uploaded yet.</p>
@else
    <div class="row">
        @foreach($videos as $video)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    @php
                        $embedLink = str_replace('watch?v=', 'embed/', $video->link);
                    @endphp
                    <iframe width="100%" height="200" src="{{ $embedLink }}" frameborder="0" allowfullscreen></iframe>

                    <div class="card-body">
                        @if($video->caption)
                            <p class="card-text">{{ $video->caption }}</p>
                        @endif

                        <form action="{{ route('videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Delete this video?')">
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