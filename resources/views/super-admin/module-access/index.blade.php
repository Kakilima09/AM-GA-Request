@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Manajemen Akses Modul</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Super Admin</li>
            </ol>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Pengaturan Role</h4>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModuleModal">
                    <i class="fas fa-plus"></i> Tambah Modul
                </button>
            </div>
            <div class="card-body">

                {{-- FORM UPDATE --}}
                <form action="{{ route('super-admin.module-access.update') }}"
                    method="POST"
                    id="moduleAccessForm">

                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">

                            <thead class="thead-light">
                                <tr>
                                    <th style="width:30%;">Nama Modul</th>
                                    <th style="width:20%;">Admin AM</th>
                                    <th style="width:20%;">Admin GA</th>
                                    <th style="width:20%;">User</th>
                                    <th style="width:10%;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($allModules as $module)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ ucwords(str_replace('-', ' ', $module)) }}
                                        </strong>
                                    </td>

                                    @foreach($roles as $role)

                                    <td>

                                        <div class="custom-control custom-switch">

                                            <input
                                                type="checkbox"
                                                class="custom-control-input"

                                                name="access[{{ $role }}][{{ $module }}]"

                                                value="1"

                                                id="switch-{{ Str::slug($role) }}-{{ Str::slug($module) }}"

                                                {{
                                                    isset($accesses[$role])
                                                    && $accesses[$role]
                                                        ->where('module_name', $module)
                                                        ->first()
                                                    && $accesses[$role]
                                                        ->where('module_name', $module)
                                                        ->first()
                                                        ->is_active
                                                    ? 'checked'
                                                    : ''
                                                }}
                                            >

                                            <label
                                                class="custom-control-label"
                                                for="switch-{{ Str::slug($role) }}-{{ Str::slug($module) }}"
                                            >
                                            </label>

                                        </div>

                                    </td>

                                    @endforeach

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $loop->index }}')"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </td>

                                </tr>

                                @empty

                                <tr>
                                    <td colspan="5" class="text-center">
                                        Belum ada modul.
                                    </td>
                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <div class="row mt-3">

                        <div class="col-md-12">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="saveBtn"
                            >

                                <i
                                    class="fas fa-spinner fa-spin d-none"
                                    id="loadingSpinner"
                                ></i>

                                <span id="saveText">
                                    Simpan Pengaturan
                                </span>

                            </button>


                            <a
                                href="{{ route('dashboard') }}"
                                class="btn btn-secondary"
                            >
                                Batal
                            </a>

                        </div>

                    </div>

                </form>


                {{-- DELETE FORM DI LUAR FORM UPDATE --}}
                @foreach($allModules as $module)

                <form
                    id="delete-form-{{ $loop->index }}"
                    action="{{ route('super-admin.module-access.destroy', $module) }}"
                    method="POST"
                    style="display: none;"
                >

                    @csrf
                    @method('DELETE')

                </form>

                @endforeach

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Modul -->
<div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModuleModalLabel">Tambah Modul Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('super-admin.module-access.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Modul <span class="text-danger">*</span></label>
                        <input type="text" name="module_name" class="form-control" placeholder="Contoh: module-name" required>
                        <small class="text-muted">Gunakan huruf kecil dan tanda hubung (-)</small>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <input type="text" name="description" class="form-control" placeholder="Deskripsi modul">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

<script>

function confirmDelete(index) {

    if (confirm('Yakin ingin menghapus modul ini?')) {

        document
            .getElementById('delete-form-' + index)
            .submit();

    }

}


document
    .getElementById('moduleAccessForm')
    .addEventListener('submit', function() {

        document
            .getElementById('loadingSpinner')
            .classList
            .remove('d-none');


        document
            .getElementById('saveText')
            .textContent = 'Menyimpan...';


        document
            .getElementById('saveBtn')
            .disabled = true;

    });

</script>

@endpush
@endsection
