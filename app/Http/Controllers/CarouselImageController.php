<?php

namespace App\Http\Controllers;

use App\Models\CarouselImage;
use Illuminate\Http\Request;

class CarouselImageController extends Controller
{
    public function index()
    {
        $images = CarouselImage::orderBy('order')->get();
        return view('admin.carousel.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        CarouselImage::create($request->all());

        return redirect()->back()->with('success', 'Gambar carousel berhasil ditambahkan.');
    }

    public function update(Request $request, CarouselImage $carousel)
    {
        $request->validate([
            'image_url' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $carousel->update($request->all());

        return redirect()->back()->with('success', 'Gambar carousel berhasil diperbarui.');
    }

    public function destroy(CarouselImage $carousel)
    {
        $carousel->delete();
        return redirect()->back()->with('success', 'Gambar carousel berhasil dihapus.');
    }
}
