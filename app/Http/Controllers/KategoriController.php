<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $rsetKategori = Kategori::select('id', 'kategori', 'jenis', 
            \DB::raw('(CASE
                WHEN jenis = "M" THEN "Modal"
                WHEN jenis = "A" THEN "Alat"
                WHEN jenis = "BHP" THEN "Bahan Habis Pakai"
                ELSE "Bahan Tidak Habis Pakai"
                END) AS ketKategori'))
            ->when($search, function($query) use ($search) {
                // Jika terdapat kata kunci pencarian, filter berdasarkan nama kategori atau jenis
                $query->where('kategori', 'like', "%$search%");
            })

            ->paginate(10);

        return view('kategori.index', compact('rsetKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aKategori = [
            'blank' => 'Pilih Kategori',
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];

        return view('kategori.create', compact('aKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'jenis' => 'required|in:M,A,BHP,BTHP',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Kategori::create([
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rsetKategori = Kategori::findOrFail($id);
        return view('kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $aKategori = [
            'blank' => 'Pilih Kategori',
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];

        $rsetKategori = Kategori::findOrFail($id);

        return view('kategori.edit', compact('rsetKategori', 'aKategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'jenis' => 'required|in:M,A,BHP,BTHP',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $rsetKategori = Kategori::findOrFail($id);

        $rsetKategori->update([
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()) {
             return redirect()->route('kategori.index')->with(['gagal' => 'Data Gagal Dihapus!']);
        } else {
            $rsetKategori = Kategori::findOrFail($id);
            $rsetKategori->delete();
            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }
    }
}
