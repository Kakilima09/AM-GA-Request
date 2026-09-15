@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Service Kendaraan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-service-kendaraan.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Form Service Kendaraan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('am-service-kendaraan.update', $service) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_polisi">No. Polisi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                       id="no_polisi" name="no_polisi" value="{{ old('no_polisi', $service->no_polisi) }}">
                                @error('no_polisi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="merk_type">Merk / Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk_type') is-invalid @enderror"
                                       id="merk_type" name="merk_type" value="{{ old('merk_type', $service->merk_type) }}">
                                @error('merk_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="km">KM <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('km') is-invalid @enderror"
                                       id="km" name="km" value="{{ old('km', $service->km) }}">
                                @error('km') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keluhan">Keluhan</label>
                                <textarea class="form-control @error('keluhan') is-invalid @enderror"
                                          id="keluhan" name="keluhan" rows="2">{{ old('keluhan', $service->keluhan) }}</textarea>
                                @error('keluhan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Perbaikan / Penggantian</h5>
                    <div class="row">
                        @php
                            $checkboxes = ['oli','tune_up','rem','ac','kopling','lampu','accu','filter','balancing','spooring','ban','wiper','overhaul'];
                            $labels = ['Oli','Tune Up','Rem','AC','Kopling','Lampu','Accu','Filter','Balancing','Spooring','Ban','Wiper','Overhaul'];
                        @endphp
                        @foreach ($checkboxes as $idx => $field)
                            <div class="col-md-3 col-sm-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{ $field }}"
                                           name="{{ $field }}" value="1" {{ old($field, $service->$field) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{ $field }}">{{ $labels[$idx] }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lain_lain">Lain-lain</label>
                                <input type="text" class="form-control" id="lain_lain" name="lain_lain"
                                       value="{{ old('lain_lain', $service->lain_lain) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update Service</button>
                            <a href="{{ route('am-service-kendaraan.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
