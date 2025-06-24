@extends('layouts.app')
@if (!Request::is('/'))
    @include('layouts.navigation')
@endif


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
{{-- Header --}}
<header class="bg-white shadow-sm fixed top-0 inset-x-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
        {{-- Logo & Nama Rumah Sakit --}}
        <a href="{{ url('/') }}" class="flex items-center space-x-3">
            @if(!empty($rs->logo_url))
                <img src="{{ asset($rs->logo_url) }}" alt="Logo RS"
                     class="h-10 w-10 rounded-full object-cover bg-white border shadow">
            @else
                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 font-bold flex items-center justify-center text-xl">
                    RS
                </div>
            @endif
            <div class="leading-tight">
                <div class="text-lg font-bold text-slate-900">{{ $rs->nama_rs ?? 'Rumah Sakit' }}</div>
                <div class="text-sm text-gray-500">{{ $rs->alamat ?? 'Alamat Belum Tersedia' }}</div>
            </div>
        </a>

        {{-- Login/Register atau Profile --}}
        <div class="flex items-center space-x-4">
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-sm text-gray-700 hover:text-teal-600 font-medium flex items-center">
                        {{ Auth::user()->name }}
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.5 7.5l4.5 4.5 4.5-4.5" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-teal-600 font-medium">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-teal-600 font-medium">Register</a>
                @endif
            @endauth
        </div>
    </div>
</header>

<main class="bg-white pt-24">

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-teal-600 to-indigo-600 text-white pb-24 pt-16 relative">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex justify-center mb-6">
                @if(!empty($rs->logo_url))
                    <img src="{{ asset($rs->logo_url) }}" alt="Logo RS"
                         class="h-20 w-20 rounded-full shadow border-4 border-white object-cover bg-white">
                @else
                    <div class="w-20 h-20 rounded-full bg-white text-teal-700 flex items-center justify-center text-2xl font-bold shadow">
                        RS
                    </div>
                @endif
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $rs->nama_rs ?? 'Rumah Sakit' }}</h1>
            <p class="text-lg text-white/90 max-w-xl mx-auto">
                Melayani dengan Hati, Menyembuhkan dengan Profesional
            </p>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-white/20 backdrop-blur-sm rounded-lg py-6 px-4">
                    <div class="text-3xl font-bold">24/7</div>
                    <p class="text-sm mt-1">Layanan Darurat</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg py-6 px-4">
                    <div class="text-3xl font-bold">{{ $dokter->count() }}+</div>
                    <p class="text-sm mt-1">Dokter Spesialis</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg py-6 px-4">
                    <div class="text-3xl font-bold">{{ $pengumuman->count() }}</div>
                    <p class="text-sm mt-1">Pengumuman Aktif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Profil Rumah Sakit --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-700 mb-6">
                <i class="fas fa-hospital-alt text-teal-600 mr-2"></i> Profil Rumah Sakit
            </h2>
            @if($rs)
                <div class="space-y-3 text-slate-600">
                    <div><strong>Alamat:</strong> {{ $rs->alamat }}</div>
                    <div><strong>Email:</strong> <a href="mailto:{{ $rs->email }}" class="text-blue-600 hover:underline">{{ $rs->email }}</a></div>
                    <div><strong>Telepon:</strong> <a href="tel:{{ $rs->no_telepon }}" class="text-blue-600 hover:underline">{{ $rs->no_telepon }}</a></div>
                    <div><strong>Jam Operasional:</strong> {{ $rs->jam_operasional }}</div>
                </div>
            @else
                <p class="text-slate-500">Informasi rumah sakit belum tersedia.</p>
            @endif
        </div>
    </section>

    {{-- Pengumuman --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-700 mb-6">
                <i class="fas fa-bullhorn text-teal-600 mr-2"></i> Pengumuman Terbaru
            </h2>
            @forelse($pengumuman as $item)
                <div class="bg-slate-100 p-4 rounded-lg mb-4 border-l-4 border-teal-500 shadow-sm">
                    <h3 class="text-slate-800 font-semibold mb-1">
                        {{ $item->judul }}
                        @switch($item->tipe_pengumuman)
                            @case('promo') 🎉 @break
                            @case('layanan_baru') 🆕 @break
                            @case('jadwal') 📅 @break
                            @default 📢
                        @endswitch
                    </h3>
                    <p class="text-slate-600 text-sm">{{ Str::limit($item->konten, 100) }}</p>
                    <div class="text-xs text-slate-400 mt-1">{{ $item->created_at->format('d M Y') }}</div>
                </div>
            @empty
                <p class="text-slate-500">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </section>

    {{-- Tim Dokter --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-700 mb-6">
                <i class="fas fa-user-md text-teal-600 mr-2"></i> Tim Dokter Spesialis
            </h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($dokter->where('status_aktif', 'aktif') as $dr)
                    <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition border">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-teal-500 to-indigo-500 text-white flex items-center justify-center text-xl font-bold mb-4">
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
                @empty
                    <p class="text-slate-500">Belum ada data dokter.</p>
                @endforelse
            </div>
        </div>
    </section>

</main>

<footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500">
    <p>&copy; {{ date('Y') }} {{ $rs->nama_rs ?? 'Rumah Sakit' }}. All rights reserved.</p>
</footer>
@endsection
