@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        {{-- Logo Rumah Sakit --}}
        <div class="flex items-center space-x-2">
            @if(!empty($rs->logo_url))
                <img src="{{ asset('storage/' . $rs->logo_url) }}" alt="Logo RS" class="h-10 w-10 object-cover rounded-full shadow">
            @else
                <div class="text-2xl font-bold text-teal-700 flex items-center">
                    <i class="fas fa-hospital-symbol mr-2"></i> {{ $rs->nama_rs ?? 'Rumah Sakit' }}
                </div>
            @endif
        </div>

        {{-- Login/Register --}}
        <div class="space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-700 hover:text-teal-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-teal-600">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-medium text-slate-700 hover:text-teal-600">Register</a>
                @endif
            @endauth
        </div>
    </div>
</header>

<main class="pt-28">

    {{-- Hero Section --}}
    <section class="pb-20 bg-gradient-to-br from-teal-500 to-indigo-600 text-white text-center relative">
        <div class="max-w-3xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                {{ $rs->nama_rs ?? 'Selamat Datang di Rumah Sakit Kami' }}
            </h1>
            <p class="text-lg text-slate-100 mb-6">
                Melayani dengan Hati, Menyembuhkan dengan Profesional
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <div class="bg-white/10 backdrop-blur rounded-xl py-6 px-4 shadow-lg">
                    <div class="text-3xl font-semibold">24/7</div>
                    <div class="text-sm text-slate-100 mt-2">Layanan Darurat</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl py-6 px-4 shadow-lg">
                    <div class="text-3xl font-semibold">{{ $dokter->count() }}+</div>
                    <div class="text-sm text-slate-100 mt-2">Dokter Spesialis</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl py-6 px-4 shadow-lg">
                    <div class="text-3xl font-semibold">{{ $pengumuman->count() }}</div>
                    <div class="text-sm text-slate-100 mt-2">Pengumuman Aktif</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Profil Rumah Sakit --}}
    <section class="py-14 bg-slate-50">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-slate-700 mb-6 flex items-center">
                <i class="fas fa-hospital-alt text-teal-600 mr-2"></i> Profil Rumah Sakit
            </h2>
            @if($rs)
                <div class="space-y-3">
                    <div><strong class="text-slate-600">Alamat:</strong> {{ $rs->alamat }}</div>
                    <div><strong class="text-slate-600">Email:</strong> <a href="mailto:{{ $rs->email }}" class="text-blue-600 hover:underline">{{ $rs->email }}</a></div>
                    <div><strong class="text-slate-600">Telepon:</strong> <a href="tel:{{ $rs->no_telepon }}" class="text-blue-600 hover:underline">{{ $rs->no_telepon }}</a></div>
                    <div><strong class="text-slate-600">Jam Operasional:</strong> {{ $rs->jam_operasional }}</div>
                </div>
            @else
                <p class="text-slate-500">Informasi rumah sakit belum tersedia.</p>
            @endif
        </div>
    </section>

    {{-- Pengumuman --}}
    <section class="py-14 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-slate-700 mb-6 flex items-center">
                <i class="fas fa-bullhorn text-teal-600 mr-2"></i> Pengumuman Terbaru
            </h2>

            @forelse($pengumuman as $item)
                <div class="bg-slate-100 p-4 rounded-lg mb-4 border-l-4 border-teal-500">
                    <h3 class="text-slate-800 font-semibold mb-1">
                        @switch($item->tipe_pengumuman)
                            @case('info') ℹ️ @break
                            @case('promo') 🎉 @break
                            @case('layanan_baru') 🆕 @break
                            @case('jadwal') 📅 @break
                            @default 📢
                        @endswitch
                        {{ $item->judul }}
                    </h3>
                    <p class="text-slate-600 text-sm">{{ Str::limit($item->konten, 100) }}</p>
                    <div class="text-xs text-slate-400 mt-1">
                        {{ $item->created_at->format('d M Y') }}
                        @if($item->tipe_pengumuman)
                            • <span class="uppercase text-teal-600">{{ str_replace('_', ' ', $item->tipe_pengumuman) }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-slate-500">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </section>

    {{-- Daftar Dokter --}}
    <section class="py-14 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-slate-700 mb-6 flex items-center">
                <i class="fas fa-user-md text-teal-600 mr-2"></i> Tim Dokter Spesialis
            </h2>

            @if($dokter->count())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($dokter->where('status_aktif', 'aktif') as $dr)
                        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border border-slate-100">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-teal-500 to-indigo-500 text-white flex items-center justify-center text-2xl font-bold mb-4">
                                    {{ strtoupper(substr($dr->nama_dokter, 0, 2)) }}
                                </div>
                                <h3 class="text-lg font-semibold text-slate-800 text-center">
                                    {{ $dr->gelar }} {{ $dr->nama_dokter }}
                                </h3>
                                <div class="text-sm text-teal-700 mt-1">
                                    {{ $dr->spesialisasi->nama_spesialisasi ?? 'Dokter Umum' }}
                                </div>
                                @if($dr->pengalaman_tahun)
                                    <div class="text-sm text-slate-500 mt-1">
                                        <i class="fas fa-user-graduate mr-1 text-teal-400"></i>
                                        {{ $dr->pengalaman_tahun }} tahun pengalaman
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-slate-500">
                    <i class="fas fa-user-md text-3xl mb-2"></i>
                    <p>Tim dokter belum tersedia.</p>
                </div>
            @endif
        </div>
    </section>

</main>

<footer class="bg-white border-t border-slate-100 py-6 text-center text-sm text-slate-500">
    <p>&copy; {{ date('Y') }} {{ $rs->nama_rs ?? 'Rumah Sakit' }}. All rights reserved.</p>
</footer>
@endsection
