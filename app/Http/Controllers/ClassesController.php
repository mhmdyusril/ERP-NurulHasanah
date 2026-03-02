<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Http\Requests\StoreClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Models\User;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classes::with('waliKelas')->withCount('students')->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'guru')->get();
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassesRequest $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:classes',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Classes::create($validated);
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $class)
    {
        $class->load('waliKelas', 'students');
        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $class)
    {
        $teachers = User::where('role', 'guru')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassesRequest $request, Classes $class)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:classes,nama_kelas,' . $class->id,
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $class->update($validated);
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        if ($class->students()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa.');
        }

        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
