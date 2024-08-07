<x-app-layout>
    <div class="pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mb-4">
                    <a href="/sekolah" class="btn btn-secondary">Back</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('sekolah.update', $spurchased->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Tahun Pembelian</label>
                                <input type="text" name="tahun_pembelian" class="form-control" autofocus
                                    autocomplete="off" value="{{ $spurchased->tahun_pembelian }}">
                                @error('tahun_pembelian')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" autofocus
                                    autocomplete="off" value="{{ $spurchased->nama_barang }}">
                                @error('nama_barang')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode" class="form-control" autofocus autocomplete="off"
                                    value="{{ $spurchased->kode }}">
                                @error('kode')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" name="harga_satuan" class="form-control" autofocus
                                    autocomplete="off" value="{{ $spurchased->harga_satuan }}">
                                @error('harga_satuan')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" autofocus autocomplete="off"
                                    value="{{ $spurchased->jumlah }}">
                                @error('jumlah')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Total Harga</label>
                                <input type="text" name="total_harga" class="form-control" autofocus
                                    autocomplete="off" value="{{ $spurchased->total_harga }}">
                                @error('total_harga')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Pembeli</label>
                                <input type="text" name="pembeli" class="form-control" autofocus autocomplete="off"
                                    value="{{ $spurchased->pembeli }}">
                                @error('pembeli')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label">Toko</label>
                                <input type="text" name="toko" class="form-control" autofocus autocomplete="off"
                                    value="{{ $spurchased->toko }}">
                                @error('toko')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <label class="form-label" for="textarea">Keterangan</label>
                                <textarea name="keterangan" class="form-control" autofocus autocomplete="off">{{ old('keterangan', $spurchased->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
