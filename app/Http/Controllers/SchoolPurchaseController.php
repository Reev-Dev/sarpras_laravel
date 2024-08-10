<?php

namespace App\Http\Controllers;

use ZipArchive;
use Barryvdh\DomPDF\Facade\Pdf;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use App\Models\SchoolPurchase;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Response;

class SchoolPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolPurchase::query();

        if ($request->filled('tahun_pembelian')) {
            $query->whereYear('tanggal_pembelian', $request->tahun_pembelian);
        }

        // if ($request->filled('nama_barang')) {
        //     $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        // }

        $schoolPurchases = $query->paginate(10); // Menggunakan paginate alih-alih get

        return view('admin.sekolah.index', compact('schoolPurchases'));
    }

    public function goodItems()
    {
        $schoolPurchases = SchoolPurchase::all();
        return view("admin.sekolah.goodItems", compact("schoolPurchases"));
    }

    public function damagedItems()
    {
        $schoolPurchases = SchoolPurchase::all();
        return view("admin.sekolah.damagedItems", compact("schoolPurchases"));
    }

    public function filterByYear(Request $request)
    {
        $query = SchoolPurchase::query();

        // Get the list of years
        $years = SchoolPurchase::selectRaw('YEAR(tanggal_pembelian) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Filter by year if selected
        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('tanggal_pembelian', $request->year);
        }

        $schoolPurchases = $query->get();

        return view('admin.sekolah.index', compact('schoolPurchases', 'years'));
    }

    public function store(Request $request)
    {
        // dd($request->all());/
        $request->validate([
            "tanggal_pembelian" => "required",
            "nama_barang" => "required",
            "kode" => "required|unique:school_purchases,kode",
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

        $schoolPurchase = SchoolPurchase::create($data);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->storeAs('school_purchases', $file->getClientOriginalName(), 'public');
                $schoolPurchase->images()->create(['path' => $path]);
            }
        }

        return redirect("/school-purchase")->with("success", "Berhasil menambahkan data Pembelian Sekolah.");
    }

    public function edited($id)
    {
        $schoolPurchase = SchoolPurchase::findOrFail($id);
        return view("admin.sekolah.edit", compact("schoolPurchase"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "tanggal_pembelian" => "required",
            "nama_barang" => "required",
            "kode" => "required|unique:school_purchases,kode," . $id,
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

        $schoolPurchase = SchoolPurchase::findOrFail($id);
        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->storeAs('school_purchases', $file->getClientOriginalName(), 'public');
                $schoolPurchase->images()->create(['path' => $path]);
            }
        }

        $schoolPurchase->update($data);

        return redirect("/school-purchase")->with("success", "Berhasil memperbarui data Pembelian Sekolah.");
    }

    public function getDamaged($id)
    {
        $schoolPurchase = SchoolPurchase::findOrFail($id);
        return view("admin.sekolah.damagedItems", compact("schoolPurchase"));
    }

    public function showForm()
    {
        $schoolPurchases = SchoolPurchase::all();
        return view('admin.sekolah.damagedItems', compact('schoolPurchases'));
    }

    public function edit($id)
    {
        $schoolPurchase = SchoolPurchase::findOrFail($id);
        return response()->json($schoolPurchase);
    }

    public function damaged(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jumlah_rusak' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        // Temukan data pembelian sekolah
        $schoolPurchase = SchoolPurchase::findOrFail($id);

        // Pastikan jumlah rusak yang dimasukkan tidak melebihi jumlah baik yang tersedia
        if ($request->jumlah_rusak > $schoolPurchase->jumlah_baik) {
            return redirect()->back()->withErrors(['jumlah_rusak' => 'Jumlah rusak tidak boleh melebihi jumlah baik yang tersedia.']);
        }

        // Kurangi jumlah baik dengan jumlah rusak yang dimasukkan
        $schoolPurchase->jumlah_baik -= $request->jumlah_rusak;
        $schoolPurchase->jumlah_rusak += $request->jumlah_rusak;
        $schoolPurchase->keterangan = $request->keterangan;
        $schoolPurchase->save();

        return redirect('/damaged-items-school')->with('success', 'Data barang rusak berhasil diperbarui.');
    }


    public function destroy($id)
    {
        SchoolPurchase::findOrFail($id)->delete();
        return redirect("/school-purchase")->with("success", "Berhasil menghapus data Pembelian Sekolah.");
    }

    public function print()
    {
        $schoolPurchases = SchoolPurchase::all();
        $pdf = Pdf::loadView('admin.sekolah.print', compact('schoolPurchases'));
        return $pdf->stream('Sarpras Sekolah.pdf');
    }
    public function pdf()
    {
        $schoolPurchases = SchoolPurchase::get();
        return view("admin.sekolah.print", compact("schoolPurchases"));
    }

    public function download($id)
    {
        $schoolPurchase = SchoolPurchase::findOrFail($id);
        $images = $schoolPurchase->images;

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
