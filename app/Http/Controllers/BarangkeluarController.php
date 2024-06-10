<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Barangkeluar;
use App\Models\Kategori;

class BarangkeluarController extends Controller
{
    public function index()
    {
        $rsetBarangkeluar = Barangkeluar::with('barang')->latest()->paginate(10);

        return view('barangkeluar.index', compact('rsetBarangkeluar'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $abarang = Barang::all();
        return view('barangkeluar.create', compact('abarang'));
    }

    public function store(Request $request)
    {
        $barang = Barang::find($request->barang_id);

        if (is_null($barang)) {
            return redirect()->back()->withErrors(['barang_id' => 'Barang tidak ditemukan.'])->withInput();
        }

        $request->validate([
            'tgl_keluar' => 'required|after_or_equal:today',
            'qty_keluar' => 'required|numeric|min:1|max:' . $barang->stok,
            'barang_id' => 'required|not_in:blank',
        ]);

        // Cari record barang keluar dengan tanggal dan barang yang sama
        $existingBarangKeluar = Barangkeluar::where('tgl_keluar', $request->tgl_keluar)
                                            ->where('barang_id', $request->barang_id)
                                            ->first();

        if ($existingBarangKeluar) {
            // Jika barang keluar sudah ada, tambahkan jumlah keluar
            $existingBarangKeluar->update([
                'qty_keluar' => $existingBarangKeluar->qty_keluar + $request->qty_keluar,
            ]);
        } else {
            // Jika barang keluar belum ada, buat record baru
            Barangkeluar::create([
                'tgl_keluar' => $request->tgl_keluar,
                'qty_keluar' => $request->qty_keluar,
                'barang_id' => $request->barang_id,
            ]);
        }

        // Kurangi stok barang
        $barang->update([
            'stok' => $barang->stok - $request->qty_keluar,
        ]);

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Barang Keluar Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetBarangkeluar = Barangkeluar::find($id);
        return view('barangkeluar.show', compact('rsetBarangkeluar'));
    }

    public function edit(string $id)
    {
        $abarang = Barang::all();
        $rsetBarangkeluar = Barangkeluar::find($id);
        $selectedBarang = Barang::find($rsetBarangkeluar->barang_id);

        return view('barangkeluar.edit', compact('rsetBarangkeluar', 'abarang', 'selectedBarang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_keluar' => 'required|after_or_equal:today',
            'qty_keluar' => 'required|numeric|min:1',
            'barang_id' => 'required|not_in:blank',
        ]);

        $rsetBarangkeluar = Barangkeluar::find($id);
        $barang = Barang::find($rsetBarangkeluar->barang_id);

        // Kembalikan stok barang sebelum diperbarui
        $barang->update([
            'stok' => $barang->stok + $rsetBarangkeluar->qty_keluar,
        ]);

        // Perbarui data barang keluar
        $rsetBarangkeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        // Kurangi stok barang setelah diperbarui
        $barang->update([
            'stok' => $barang->stok - $request->qty_keluar,
        ]);

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy(string $id)
    {
        $rsetBarangkeluar = Barangkeluar::find($id);
        $barang = Barang::find($rsetBarangkeluar->barang_id);

        // Kembalikan stok barang sebelum dihapus
        $barang->update([
            'stok' => $barang->stok + $rsetBarangkeluar->qty_keluar,
        ]);

        // Hapus data barang keluar
        $rsetBarangkeluar->delete();

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
