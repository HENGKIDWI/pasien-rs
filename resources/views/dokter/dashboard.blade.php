@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Selamat datang, {{ Auth::user()->name }}</h2>
    <ul>
        <li><a href="{{ route('dokter.antrian') }}">Lihat Antrian Hari Ini</a></li>
        <li><a href="{{ route('dokter.gaji') }}">Gaji Bulan Ini</a></li>
    </ul>
</div>
@endsection
