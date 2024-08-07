<x-app-layout>
    <livewire:layout.header />

    <!-- Tambahkan Tombol "Tambah" di sini -->
    <div class="col-12 max-w-7xl mx-auto sm:px-6 lg:px-8 my-3">
        <div class="row row-cards">
            <div class="col-12">
                <div class="mb-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">Tambah
                    </button>
                </div>
            </div>

            <!-- Modal untuk Pilih Barang -->
            <div class="modal modal-blur fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">Pilih Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-control" id="selectItem" required>
                                <option value="">Pilih Nama Barang</option>
                                @foreach ($dormPurchases as $purchase)
                                    <option value="{{ $purchase->id }}">{{ $purchase->nama_barang }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary mt-3" id="nextModalButton"
                                data-bs-dismiss="modal">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Edit Barang -->
            <div class="modal modal-blur fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editItemModalLabel">Tambah Barang Rusak</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="updateItemForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="jumlah_rusak" class="form-label">Jumlah Rusak</label>
                                    <input type="number" class="form-control" id="jumlah_rusak" name="jumlah_rusak"
                                        min="1" required>
                                    @error('jumlah_rusak')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan"
                                        required>
                                    @error('keterangan')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
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
                                @php
                                    $i = 1;
                                    $dataExists = false;
                                @endphp
                                @foreach ($dormPurchases as $item)
                                    @if ($item->jumlah_rusak > 0)
                                        @php
                                            $dataExists = true;
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->tanggal_pembelian }}</td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->jumlah_rusak }}</td>
                                            <td>
                                                <span class="badge bg-red text-red-fg">Rusak</span>
                                            </td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if (!$dataExists)
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectedItemId;

            document.getElementById('nextModalButton').addEventListener('click', function() {
                selectedItemId = document.getElementById('selectItem').value;

                if (selectedItemId) {
                    fetch(`/damaged-items-dorm/${selectedItemId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('jumlah_rusak').value = '';
                            document.getElementById('keterangan').value = data.keterangan || '';
                            document.getElementById('updateItemForm').action =
                                `/damaged-items-dorm/${selectedItemId}`;

                            var editModal = new bootstrap.Modal(document.getElementById(
                                'editItemModal'));
                            editModal.show();
                        });
                }
            });

            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addItemModal'));
                myModal.show();
            @endif

            @if (session('success'))
                alert('{{ session('success') }}');
            @endif
        });
    </script>
</x-app-layout>
