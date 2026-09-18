<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $medias = Media::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.media.index', compact('medias'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'media_type' => 'required|string|max:255',
        ]);

        Media::create($data);

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $media = Media::findOrFail($id);

        return view('admin.media.edit', compact('media'));
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $data = $request->validate([
            'media_type' => 'required|string|max:255',
        ]);

        $media->update($data);

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil dihapus.');
    }
}
