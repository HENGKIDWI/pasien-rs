@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Jadwal Dokter Hari Ini</h2>

    @if($jadwal->isEmpty())
        <p>Tidak ada jadwal dokter saat ini.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Biaya Konsultasi</th>
                        <th>Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $j)
                        <tr>
                            <td>{{ $j->gelar }} {{ $j->nama_dokter }}</td>
                            <td>{{ $j->nama_spesialisasi }}</td>
                            <td>{{ ucfirst($j->hari) }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                            <td>Rp {{ number_format($j->biaya_konsultasi, 0, ',', '.') }}</td>
                            <td>
                                @if($j->sisa_kuota_hari_ini > 0)
                                    <span class="badge bg-success">{{ $j->sisa_kuota_hari_ini }} tersedia</span>
                                @else
                                    <span class="badge bg-danger">Penuh</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection