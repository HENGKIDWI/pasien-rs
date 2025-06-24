@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Gaji Bulan Ini</h2>

    @if($gaji->isEmpty())
        <p>Tidak ada data gaji.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Gaji Pokok</th>
                    <th>Komisi</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gaji as $g)
                    <tr>
                        <td>{{ $g->bulan }}</td>
                        <td>Rp {{ number_format($g->gaji_pokok, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($g->komisi_konsultasi, 0, ',', '.') }}</td>
                        <td><strong>Rp {{ number_format($g->total_gaji, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
