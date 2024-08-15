<?php

namespace App\Http\Controllers;

use ZipArchive;
use Barryvdh\DomPDF\Facade\Pdf;
use Termwind\Components\Dd;
use App\Models\DormPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DormPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = DormPurchase::query();

        if ($request->filled('tahun_pembelian')) {
            $query->whereYear('tanggal_pembelian', $request->tahun_pembelian);
        }
        if ($request->filled('bulan_pembelian')) {
            $query->whereMonth('tanggal_pembelian', $request->bulan_pembelian);
        }

        // if ($request->filled('nama_barang')) {
        //     $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        // }

        $dormPurchases = $query->paginate(10); // Menggunakan paginate alih-alih get

        return view('admin.asrama.index', compact('dormPurchases'));
    }
    public function goodItems()
    {
        $dormPurchases = DormPurchase::all();
        return view("admin.asrama.goodItems", compact("dormPurchases"));
    }
    public function damagedItems()
    {
        $dormPurchases = DormPurchase::all();
        return view("admin.asrama.damagedItems", compact("dormPurchases"));
    }
    public function store(Request $request)
    {
        // dd($request->all());/
        $request->validate([
            "tanggal_pembelian" => "required",
            "nama_barang" => "required",
            "kode" => "required|unique:dorm_purchases,kode",
            "harga_satuan" => "required",
            "jumlah_baik" => "required",
            "total_harga" => "required",
            "pembeli" => "required",
            "toko" => "required",
            "deskripsi" => "required",
            "gambar.*" => "required|mimes:jpg,jpeg,png",
        ], [
            "tanggal_pembelian.required" => "Tanggal Pembelian harus diisi",
            "nama_barang.required" => "Nama Barang harus diisi",
            "kode.required" => "Kode harus diisi",
            "kode.unique" => "Kode sudah digunakan",
            "harga_satuan.required" => "Harga Satuan harus diisi",
            "jumlah_baik.required" => "Jumlah harus diisi",
            "total_harga.required" => "Total Harga harus diisi",
            "pembeli.required" => "Pembeli harus diisi",
            "toko.required" => "Toko harus diisi",
            "deskripsi.required" => "Deskripsi harus diisi",
            "gambar.required" => "Upload gambar",
            "gambar.mimes" => "Gambar harus berupa file dengan format: jpg, jpeg, png",
        ]);

        $data = $request->except('gambar');

        $dormPurchase = DormPurchase::create($data);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->storeAs('dorm_purchases', $file->getClientOriginalName(), 'public');
                $dormPurchase->images()->create(['path' => $path]);
            }
        }

        return redirect("/dorm-purchase")->with("success", "Berhasil menambahkan data Pembelian Asrama.");
    }

    public function edited($id)
    {
        $dormPurchase = DormPurchase::findOrFail($id);
        return view("admin.asrama.edit", compact("dormPurchase"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "tanggal_pembelian" => "required",
            "nama_barang" => "required",
            "kode" => "required|unique:dorm_purchases,kode," . $id,
            "harga_satuan" => "required",
            "jumlah" => "required",
            "total_harga" => "required",
            "pembeli" => "required",
            "toko" => "required",
            "deskripsi" => "required",
            'gambar.*' => "nullable|mimes:jpg,jpeg,png|max:2048",
        ], [
            "tanggal_pembelian.required" => "Tanggal Pembelian harus diisi",
            "nama_barang.required" => "Nama Barang harus diisi",
            "kode.required" => "Kode harus diisi",
            "kode.unique" => "Kode sudah digunakan",
            "harga_satuan.required" => "Harga Satuan harus diisi",
            "jumlah.required" => "Jumlah harus diisi",
            "total_harga.required" => "Total Harga harus diisi",
            "pembeli.required" => "Pembeli harus diisi",
            "toko.required" => "Toko harus diisi",
            "deskripsi.required" => "Deskripsi harus diisi",
            "gambar.mimes" => "Gambar harus berupa file dengan format: jpg, jpeg, png",
            "gambar.max" => "Ukuran gambar maksimal adalah 2MB",
        ]);

        $dormPurchase = DormPurchase::findOrFail($id);

        // Update data selain gambar
        $dormPurchase->update($request->except(['gambar']));

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari database dan storage
            $oldImages = $dormPurchase->images;
            foreach ($oldImages as $image) {
                Storage::disk('public')->delete('dorm_purchases/' . $image->path);
                $image->delete();
            }

            // Simpan gambar baru
            foreach ($request->file('gambar') as $file) {
                $path = $file->storeAs('dorm_purchases', $file->getClientOriginalName(), 'public');
                $dormPurchase->images()->create(['path' => $path]);
            }
        }

        return redirect("/dorm-purchase")->with("success", "Berhasil memperbarui data Pembelian Asrama.");
    }
    public function getDamaged($id)
    {
        $dormPurchase = DormPurchase::findOrFail($id);
        return view("admin.asrama.damagedItems", compact("dormPurchase"));
    }
    public function showForm()
    {
        $dormPurchases = DormPurchase::all();
        return view('admin.asrama.damagedItems', compact('dormPurchases'));
    }
    public function edit($id)
    {
        $dormPurchase = DormPurchase::findOrFail($id);
        return response()->json($dormPurchase);
    }
    public function damaged(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jumlah_rusak' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        // Temukan data pembelian sekolah
        $dormPurchase = DormPurchase::findOrFail($id);

        // Pastikan jumlah rusak yang dimasukkan tidak melebihi jumlah baik yang tersedia
        if ($request->jumlah_rusak > $dormPurchase->jumlah_baik) {
            return redirect()->back()->withErrors(['jumlah_rusak' => 'Jumlah rusak tidak boleh melebihi jumlah baik yang tersedia.']);
        }

        // Kurangi jumlah baik dengan jumlah rusak yang dimasukkan
        $dormPurchase->jumlah_baik -= $request->jumlah_rusak;
        $dormPurchase->jumlah_rusak += $request->jumlah_rusak;
        $dormPurchase->keterangan = $request->keterangan;
        $dormPurchase->save();

        return redirect('/damaged-items-dorm')->with('success', 'Data barang rusak berhasil diperbarui.');
    }
    public function destroy($id)
    {
        DormPurchase::findOrFail($id)->delete();
        return redirect("/dorm-purchase")->with("success", "Berhasil menghapus data Pembelian Asrama.");
    }
    public function print()
    {
        $dormPurchases = DormPurchase::all();
        $pdf = Pdf::loadView('admin.asrama.print', compact('dormPurchases'));
        return $pdf->stream('Sarpras Asrama.pdf');
    }
    public function pdf()
    {
        $dormPurchases = DormPurchase::get();
        return view("admin.asrama.print", compact("dormPurchases"));
    }

    public function download($id)
    {
        $dormPurchase = DormPurchase::findOrFail($id);
        $images = $dormPurchase->images;

        if ($images->count() == 1) {
            $image = $images->first();
            $path = storage_path('app/public/' . $image->path);

            if (!file_exists($path)) {
                abort(404, 'Image not found.');
            }

            return response()->download($path);
        } else {
            $zip = new ZipArchive;
            $fileName = 'download.zip';
            $zipPath = storage_path('app/public/' . $fileName);

            // Hapus file ZIP lama jika ada
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }

            // Coba buka file ZIP baru
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                foreach ($images as $image) {
                    $imagePath = storage_path('app/public/' . $image->path);

                    if (file_exists($imagePath)) {
                        $zip->addFile($imagePath, basename($image->path));
                    } else {
                        Log::warning('Image not found: ' . $imagePath);
                    }
                }
                $zip->close();

                // Periksa apakah file ZIP berhasil dibuat dan ukurannya lebih dari 0
                if (file_exists($zipPath) && filesize($zipPath) > 0) {
                    return response()->download($zipPath)->deleteFileAfterSend(true);
                } else {
                    abort(500, 'Failed to create ZIP file or ZIP file is empty.');
                }
            } else {
                abort(500, 'Could not open ZIP file.');
            }
        }
    }
}
