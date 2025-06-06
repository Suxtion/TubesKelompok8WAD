<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload gambar

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        $room = new Room();
        $room->name = $request->name;
        $room->location = $request->location;
        $room->capacity = $request->capacity;
        $room->description = $request->description;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/rooms');
            $room->image = Storage::url($imagePath);
        }

        $room->save();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $room->name = $request->name;
        $room->location = $request->location;
        $room->capacity = $request->capacity;
        $room->description = $request->description;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($room->image) {
                Storage::delete(str_replace('/storage', 'public', $room->image));
            }
            $imagePath = $request->file('image')->store('public/rooms');
            $room->image = Storage::url($imagePath);
        }

        $room->save();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if ($room->image) {
            Storage::delete(str_replace('/storage', 'public', $room->image));
        }
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}