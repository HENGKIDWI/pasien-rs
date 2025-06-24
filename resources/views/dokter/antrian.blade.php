@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Antrian Hari Ini</h2>

    @if($antrian->isEmpty())
        <p>Tidak ada pasien hari ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($antrian as $a)
                    <tr>
                        <td>{{ $a->nama_pengunjung }}</td>
                        <td>{{ $a->keluhan }}</td>
                        <td>{{ $a->jam_kunjungan }}</td>
                        <td>{{ $a->status_antrian }}</td>
                        <td>
                            @if($a->status_antrian !== 'selesai')
                            <form method="POST" action="{{ route('dokter.antrian.selesai', $a->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-success">Selesai</button>
                            </form>
                            @else
                                Selesai
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
