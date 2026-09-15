@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit BAST</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bast.index') }}">BAST</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bast.update', $bast) }}" method="POST">
                    @csrf @method('PUT')

                    {{-- Tipe --}}
                    <div class="form-group">
                        <label>Tipe BAST <span class="text-danger">*</span></label>
                        <select name="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                            <option value="fa" {{ old('tipe', $bast->tipe) == 'fa' ? 'selected' : '' }}>Fixed Asset</option>
                            <option value="jasa" {{ old('tipe', $bast->tipe) == 'jasa' ? 'selected' : '' }}>Jasa</option>
                        </select>
                        @error('tipe') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $bast->tanggal) }}" required>
                                @error('tanggal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Perusahaan <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $bast->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Departemen <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $bast->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Pemohon <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemohon" class="form-control @error('nama_pemohon') is-invalid @enderror" value="{{ old('nama_pemohon', $bast->nama_pemohon) }}" required>
                                @error('nama_pemohon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jabatan <span class="text-danger">*</span></label>
                                <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $bast->jabatan) }}" required>
                                @error('jabatan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Penerimaan (centang yang diterima)</h5>
                    <div class="row">
                        @php
                            $penerimaanFields = [
                                'penerimaan_fa' => 'Fixed Asset',
                                'penerimaan_kendaraan_r2_r4' => 'Kendaraan R2/R4',
                                'penerimaan_elektronik_it' => 'Elektronik / IT',
                                'penerimaan_peralatan_kantor' => 'Peralatan Kantor',
                                'penerimaan_lainnya' => 'Lainnya',
                                'jasa_service_kendaraan' => 'Jasa Service Kendaraan',
                                'jasa_renovasi' => 'Jasa Renovasi',
                                'jasa_service_fa_non' => 'Jasa Service FA Non',
                                'jasa_lainnya' => 'Jasa Lainnya',
                            ];
                        @endphp
                        @foreach($penerimaanFields as $key => $label)
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{ $key }}" name="{{ $key }}" value="1" {{ old($key, $bast->$key) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{ $key }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    <h5>Item FA</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="fa-items-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No FA</th>
                                    <th>Merk / Type / Spesifikasi</th>
                                    <th>Qty</th>
                                    <th>Kondisi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="fa-items-body">
                                @forelse($bast->itemsFa as $index => $item)
                                <tr class="fa-item-row">
                                    <td><input type="text" name="items_fa[{{ $index }}][no_fa]" class="form-control" value="{{ old('items_fa.'.$index.'.no_fa', $item->no_fa) }}" placeholder="FA-001"></td>
                                    <td><input type="text" name="items_fa[{{ $index }}][merk_type_spesifikasi]" class="form-control" value="{{ old('items_fa.'.$index.'.merk_type_spesifikasi', $item->merk_type_spesifikasi) }}" placeholder="Merk/Type"></td>
                                    <td><input type="number" name="items_fa[{{ $index }}][qty]" class="form-control" value="{{ old('items_fa.'.$index.'.qty', $item->qty) }}" min="1"></td>
                                    <td>
                                        <select name="items_fa[{{ $index }}][kondisi]" class="form-control">
                                            <option value="baik" {{ old('items_fa.'.$index.'.kondisi', $item->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak" {{ old('items_fa.'.$index.'.kondisi', $item->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="items_fa[{{ $index }}][keterangan]" class="form-control" value="{{ old('items_fa.'.$index.'.keterangan', $item->keterangan) }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-fa-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                @empty
                                <tr class="fa-item-row">
                                    <td><input type="text" name="items_fa[0][no_fa]" class="form-control" placeholder="FA-001"></td>
                                    <td><input type="text" name="items_fa[0][merk_type_spesifikasi]" class="form-control" placeholder="Merk/Type"></td>
                                    <td><input type="number" name="items_fa[0][qty]" class="form-control" value="1" min="1"></td>
                                    <td>
                                        <select name="items_fa[0][kondisi]" class="form-control">
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="items_fa[0][keterangan]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-fa-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-fa-row"><i class="fas fa-plus"></i> Tambah FA</button>
                    </div>

                    <hr>
                    <h5>Item Jasa</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="jasa-items-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="jasa-items-body">
                                @forelse($bast->itemsJasa as $index => $item)
                                <tr class="jasa-item-row">
                                    <td><input type="date" name="items_jasa[{{ $index }}][tanggal_permintaan]" class="form-control" value="{{ old('items_jasa.'.$index.'.tanggal_permintaan', $item->tanggal_permintaan) }}"></td>
                                    <td><input type="date" name="items_jasa[{{ $index }}][tanggal_selesai]" class="form-control" value="{{ old('items_jasa.'.$index.'.tanggal_selesai', $item->tanggal_selesai) }}"></td>
                                    <td><input type="text" name="items_jasa[{{ $index }}][keterangan]" class="form-control" value="{{ old('items_jasa.'.$index.'.keterangan', $item->keterangan) }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-jasa-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                @empty
                                <tr class="jasa-item-row">
                                    <td><input type="date" name="items_jasa[0][tanggal_permintaan]" class="form-control"></td>
                                    <td><input type="date" name="items_jasa[0][tanggal_selesai]" class="form-control"></td>
                                    <td><input type="text" name="items_jasa[0][keterangan]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-jasa-row"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-jasa-row"><i class="fas fa-plus"></i> Tambah Jasa</button>
                    </div>

                    <hr>
                    <h5>Penandatangan</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diserahkan oleh <span class="text-danger">*</span></label>
                                <input type="text" name="diserahkan_oleh" class="form-control @error('diserahkan_oleh') is-invalid @enderror" value="{{ old('diserahkan_oleh', $bast->diserahkan_oleh) }}" required>
                                @error('diserahkan_oleh') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diterima oleh <span class="text-danger">*</span></label>
                                <input type="text" name="diterima_oleh" class="form-control @error('diterima_oleh') is-invalid @enderror" value="{{ old('diterima_oleh', $bast->diterima_oleh) }}" required>
                                @error('diterima_oleh') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mengetahui</label>
                                <input type="text" name="mengetahui" class="form-control @error('mengetahui') is-invalid @enderror" value="{{ old('mengetahui', $bast->mengetahui) }}">
                                @error('mengetahui') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Menyetujui</label>
                                <input type="text" name="menyetujui" class="form-control @error('menyetujui') is-invalid @enderror" value="{{ old('menyetujui', $bast->menyetujui) }}">
                                @error('menyetujui') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update BAST</button>
                    <a href="{{ route('bast.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let faIndex = {{ $bast->itemsFa->count() }};
        $('#add-fa-row').click(function() {
            let row = `
                <tr class="fa-item-row">
                    <td><input type="text" name="items_fa[${faIndex}][no_fa]" class="form-control" placeholder="FA-001"></td>
                    <td><input type="text" name="items_fa[${faIndex}][merk_type_spesifikasi]" class="form-control" placeholder="Merk/Type"></td>
                    <td><input type="number" name="items_fa[${faIndex}][qty]" class="form-control" value="1" min="1"></td>
                    <td>
                        <select name="items_fa[${faIndex}][kondisi]" class="form-control">
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </td>
                    <td><input type="text" name="items_fa[${faIndex}][keterangan]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-fa-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            $('#fa-items-body').append(row);
            faIndex++;
        });
        $(document).on('click', '.remove-fa-row', function() {
            if ($('#fa-items-body tr').length > 1) $(this).closest('tr').remove();
        });

        let jasaIndex = {{ $bast->itemsJasa->count() }};
        $('#add-jasa-row').click(function() {
            let row = `
                <tr class="jasa-item-row">
                    <td><input type="date" name="items_jasa[${jasaIndex}][tanggal_permintaan]" class="form-control"></td>
                    <td><input type="date" name="items_jasa[${jasaIndex}][tanggal_selesai]" class="form-control"></td>
                    <td><input type="text" name="items_jasa[${jasaIndex}][keterangan]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-jasa-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            $('#jasa-items-body').append(row);
            jasaIndex++;
        });
        $(document).on('click', '.remove-jasa-row', function() {
            if ($('#jasa-items-body tr').length > 1) $(this).closest('tr').remove();
        });
    });
</script>
@endpush
