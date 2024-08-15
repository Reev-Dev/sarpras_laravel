<x-app-layout>
    <livewire:layout.header />

    <div class="col-12 max-w-7xl mx-auto sm:px-6 lg:px-8 my-3">
        <div class="row row-cards">
            <div class="col-12">
                <div class="mb-2">
                    {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">Tambah
                    </button> --}}
                </div>
            </div>

            <!-- Modal untuk Tambah Barang -->
            <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">Tambah Barang Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addItemForm" action="" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="school_purchase_id" class="form-label">Nama Barang</label>
                                    <select class="form-control" id="school_purchase_id" name="school_purchase_id"
                                        required>
                                        <option value="">Pilih Nama Barang</option>
                                        @foreach ($schoolPurchases as $purchase)
                                            <option value="{{ $purchase->id }}">{{ $purchase->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card" style="height: 22rem;">
                    <div class="card-body card-body-scrollable card-body-scrollable-shadow p-0 rounded">
                        <style>
                            .table-responsive {
                                position: relative;
                                height: 21rem;
                                overflow-y: auto;
                            }

                            .table-responsive thead th {
                                position: sticky;
                                top: 0;
                                z-index: 1;
                                background: white;
                            }

                            .table-responsive tbody tr:nth-child(odd) {
                                background-color: #f9f9f9;
                            }

                            .table th,
                            .table td {
                                white-space: nowrap;
                            }

                            .table th.w-4,
                            .table td.w-4 {
                                width: 4%;
                            }

                            .table th.w-10,
                            .table td.w-10 {
                                width: 15%;
                            }

                            .table th.w-20,
                            .table td.w-20 {
                                width: 30%;
                            }

                            .table th.w-15,
                            .table td.w-15 {
                                width: 20%;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="w-4">No</th>
                                        <th class="w-10">Tanggal Pembelian</th>
                                        <th class="w-15">Nama Barang</th>
                                        <th class="w-10">Kode Barang</th>
                                        <th class="w-10">Jumlah</th>
                                        <th class="w-10">Status</th>
                                        <th class="w-20">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $filteredSchoolPurchases = $schoolPurchases->filter(function ($item) {
                                            return $item->jumlah_baik > 0;
                                        });
                                    @endphp
                                    @if ($filteredSchoolPurchases->isNotEmpty())
                                        @foreach ($filteredSchoolPurchases as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->tanggal_pembelian }}</td>
                                                <td>{{ $item->nama_barang }}</td>
                                                <td>{{ $item->kode }}</td>
                                                <td>{{ $item->jumlah_baik }}</td>
                                                <td>
                                                    <span class="badge bg-green text-green-fg">Baik</span>
                                                </td>
                                                <td>{{ $item->deskripsi }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
