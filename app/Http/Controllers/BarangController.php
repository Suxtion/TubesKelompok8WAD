<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file gambar
use Illuminate\Support\Facades\Validator; // Untuk validasi

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $barangs = Barang::latest()->paginate(10); // Ambil data terbaru, 10 per halaman

        if ($request->expectsJson()) { // Jika request adalah API
            return response()->json([
                'success' => true,
                'message' => 'Daftar data barang berhasil diambil.',
                'data' => $barangs
            ], 200);
        }

        // Jika request adalah web
        return view('barangs.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('barangs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Server-side Validation
        $validator = Validator::make($request->all(), [
            'nama_barang'     => 'required|string|max:255|unique:barangs,nama_barang',
            'deskripsi'       => 'nullable|string',
            'jumlah_total'    => 'required|integer|min:1',
            'jumlah_tersedia' => 'required|integer|min:0|lte:jumlah_total', // lte = less than or equal to jumlah_total
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Perlu Perbaikan,Rusak Berat',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Maks 2MB
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.unique' => 'Nama barang sudah ada.',
            'jumlah_total.required' => 'Jumlah total wajib diisi.',
            'jumlah_total.min' => 'Jumlah total minimal 1.',
            'jumlah_tersedia.required' => 'Jumlah tersedia wajib diisi.',
            'jumlah_tersedia.lte' => 'Jumlah tersedia tidak boleh melebihi jumlah total.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif, svg.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pathGambar = null;
        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            // Simpan ke public/storage/gambar_barang, pastikan folder 'gambar_barang' sudah dibuat
            // dan jalankan `php artisan storage:link`
            $pathGambar = $request->file('gambar')->storeAs('gambar_barang', $namaFile, 'public');
        }

        Barang::create([
            'nama_barang'     => $request->nama_barang,
            'deskripsi'       => $request->deskripsi,
            'jumlah_total'    => $request->jumlah_total,
            'jumlah_tersedia' => $request->jumlah_tersedia,
            'kondisi'         => $request->kondisi,
            'gambar'          => $pathGambar,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Barang $barang)
    {
        if ($request->expectsJson()) { // Jika request adalah API
            return response()->json([
                'success' => true,
                'message' => 'Detail data barang berhasil diambil.',
                'data' => $barang
            ], 200);
        }
 
        return view('barangs.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        return view('barangs.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        // Server-side Validation
        $validator = Validator::make($request->all(), [
            'nama_barang'     => 'required|string|max:255|unique:barangs,nama_barang,' . $barang->id,
            'deskripsi'       => 'nullable|string',
            'jumlah_total'    => 'required|integer|min:1',
            'jumlah_tersedia' => 'required|integer|min:0|lte:jumlah_total',
            'kondisi'         => 'required|in:Baik,Rusak Ringan,Perlu Perbaikan,Rusak Berat',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.unique' => 'Nama barang sudah ada.',
            'jumlah_total.required' => 'Jumlah total wajib diisi.',
            'jumlah_total.min' => 'Jumlah total minimal 1.',
            'jumlah_tersedia.required' => 'Jumlah tersedia wajib diisi.',
            'jumlah_tersedia.lte' => 'Jumlah tersedia tidak boleh melebihi jumlah total.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pathGambar = $barang->gambar; // Default ke gambar lama
        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            // Hapus gambar lama jika ada
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $pathGambar = $request->file('gambar')->storeAs('gambar_barang', $namaFile, 'public');
        }

        $barang->update([
            'nama_barang'     => $request->nama_barang,
            'deskripsi'       => $request->deskripsi,
            'jumlah_total'    => $request->jumlah_total,
            'jumlah_tersedia' => $request->jumlah_tersedia,
            'kondisi'         => $request->kondisi,
            'gambar'          => $pathGambar,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        // Hapus gambar dari storage jika ada
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus!');
    }
}