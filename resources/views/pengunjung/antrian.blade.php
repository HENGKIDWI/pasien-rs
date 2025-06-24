@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Ambil Antrian</h2>

    <form method="POST" action="{{ route('pengunjung.ambil-antrian') }}">
        @csrf
        <div class="mb-3">
            <label for="dokter_id" class="form-label">Pilih Dokter</label>
            <select name="dokter_id" id="dokter_id" class="form-control">
                @foreach($dokter as $d)
                    <option value="{{ $d->id }}">{{ $d->gelar }} {{ $d->nama_dokter }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="keluhan" class="form-label">Keluhan</label>
            <textarea name="keluhan" id="keluhan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ambil Antrian</button>
    </form>
</div>
@endsection
