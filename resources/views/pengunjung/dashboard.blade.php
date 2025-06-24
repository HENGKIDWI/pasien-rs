@extends('layouts.app')

@section('content')
@php
    // Inisialisasi variabel untuk keamanan agar tidak ada error jika variabel tidak terdefinisi
    $antrian = $antrian ?? null;
    $dokterList = $dokterList ?? collect();
    $riwayatList = $riwayatList ?? collect();
    $allPoli = $allPoli ?? collect();
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- 
    Menginisialisasi komponen Alpine.js di elemen root.
    Logika 'bookingForm' didefinisikan di dalam tag <script> di bawah.
--}}
<div x-data="bookingForm()" class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Pesan Selamat Datang dan Notifikasi --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl text-white p-8 mb-8 shadow-lg">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-blue-100">Sistem Antrian Online kami siap melayani Anda.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Gagal</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Status Antrian Aktif --}}
        @if ($antrian)
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-check-circle-fill text-green-500 mr-2"></i> Antrian Anda Aktif
                </h3>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($antrian->status_antrian) }}
                </span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $antrian->nomor_antrian }}</div>
                    <div class="text-sm text-gray-600">Nomor Antrian</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ $antrian->gelar }} {{ $antrian->nama_dokter }}</div>
                    <div class="text-sm text-gray-600">Dokter</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($antrian->jam_kunjungan)->format('H:i') }}</div>
                    <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($antrian->tanggal_kunjungan)->translatedFormat('d M Y') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ $antrian->estimasi_waktu_tunggu }} menit</div>
                    <div class="text-sm text-gray-600">Perkiraan Waktu</div>
                </div>
            </div>
            <div class="mt-6 text-center">
                 <form action="{{ route('pengunjung.antrian.batalkan', $antrian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrian ini?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                        <i class="bi bi-x-circle mr-2"></i>Batalkan Antrian
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Menu Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Kartu Jadwal Dokter --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-calendar-week text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Jadwal Dokter</h3>
                        <p class="text-gray-600">Lihat jadwal praktik semua dokter</p>
                    </div>
                </div>
                <div class="space-y-3 flex-grow">
                    @forelse ($dokterList as $dokter)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $dokter->gelar }} {{ $dokter->nama_dokter }}</div>
                            <div class="text-sm text-gray-600">{{ $dokter->nama_spesialisasi }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Tidak ada jadwal dokter tersedia.</p>
                    @endforelse
                </div>
                <a href="{{ route('pengunjung.jadwal') }}" class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center">
                    Lihat Semua Jadwal <i class="bi bi-arrow-right-short ml-2"></i>
                </a>
            </div>
            {{-- Kartu Ambil Nomor Antrian --}}
             <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-card-checklist text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Ambil Nomor Antrian</h3>
                        <p class="text-gray-600">Dapatkan nomor antrian secara online</p>
                    </div>
                </div>
                <div class="text-center py-8 flex-grow">
                     <i class="bi bi-person-plus text-5xl text-gray-300"></i>
                     <p class="mt-2 text-gray-500">Klik tombol di bawah untuk mengambil nomor antrian baru.</p>
                </div>
                <button @click="openModal()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center">
                    Ambil Nomor Antrian <i class="bi bi-arrow-right-short ml-2"></i>
                </button>
            </div>
            {{-- Kartu Status Antrian --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow flex flex-col">
                 <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-hourglass-split text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Status Antrian Saya</h3>
                        <p class="text-gray-600">Pantau status nomor antrian Anda</p>
                    </div>
                </div>
                <div class="text-center py-8 flex-grow">
                    @if ($antrian)
                        <div class="text-4xl font-bold text-blue-600 mb-2">{{ $antrian->nomor_antrian }}</div>
                        <div class="text-gray-600">Nomor antrian Anda <span class="font-semibold">{{$antrian->status_antrian}}</span></div>
                    @else
                        <i class="bi bi-exclamation-circle text-5xl text-gray-300"></i>
                        <div class="text-gray-600 mt-2">Belum ada antrian aktif</div>
                    @endif
                </div>
                <a href="{{ route('pengunjung.status') }}" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 flex items-center justify-center">
                    Cek Status <i class="bi bi-arrow-right-short ml-2"></i>
                </a>
            </div>
            {{-- Kartu Riwayat Kunjungan --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-journal-medical text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Riwayat Kunjungan</h3>
                        <p class="text-gray-600">Lihat kembali riwayat kunjungan Anda</p>
                    </div>
                </div>
                <div class="space-y-3 flex-grow">
                     @forelse ($riwayatList as $riwayat)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($riwayat->tanggal_kunjungan)->translatedFormat('d F Y') }}</div>
                            <div class="text-sm text-gray-600">{{ $riwayat->nama_dokter }}</div>
                        </div>
                        <div class="text-green-600 text-sm font-medium">Selesai</div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Tidak ada riwayat kunjungan.</p>
                    @endforelse
                </div>
                <a href="{{ route('pengunjung.riwayat') }}" class="w-full mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center justify-center">
                    Lihat Semua Riwayat <i class="bi bi-arrow-right-short ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Modal Form Booking (Logika & Tampilan Baru) -->
        <div x-show="showBookingForm" @keydown.escape.window="closeModal()" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" style="display: none;">
            <div x-show="showBookingForm" @click.outside="closeModal()" x-transition class="bg-white rounded-2xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Ambil Nomor Antrian</h3>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                
                <form method="POST" action="{{ route('pengunjung.ambil-antrian') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="poli_id" class="block text-sm font-medium text-gray-700 mb-2">1. Pilih Poli</label>
                            <select id="poli_id" x-model="selectedPoli" @change="fetchSchedules()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                <option value="">-- Pilih Poli --</option>
                                @foreach($allPoli as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="dokter_id" class="block text-sm font-medium text-gray-700 mb-2">2. Pilih Dokter</label>
                            <div x-show="isLoading" class="text-sm text-gray-500 p-2 text-center">
                                <i class="bi bi-arrow-repeat animate-spin"></i> Memuat jadwal...
                            </div>
                            <select name="dokter_id" x-show="!isLoading && schedules.length > 0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                <option value="">-- Pilih Dokter & Biaya --</option>
                                <template x-for="schedule in schedules" :key="schedule.dokter_id">
                                    <option :value="schedule.dokter_id" x-text="formatDoctor(schedule)"></option>
                                </template>
                            </select>
                            <div x-show="!isLoading && selectedPoli && schedules.length === 0" class="text-sm text-red-500 p-2 bg-red-50 rounded-lg text-center">
                                Tidak ada dokter yang praktik di poli ini hari ini atau kuota penuh.
                            </div>
                        </div>
                        <div>
                            <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-2">3. Keluhan (Opsional)</label>
                            <textarea name="keluhan" id="keluhan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Demam dan sakit kepala..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-300">
                            Konfirmasi & Ambil Antrian
                        </button>
                        <button type="button" @click="closeModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingForm', () => ({
            showBookingForm: false,
            isLoading: false,
            selectedPoli: '',
            schedules: [],
            
            openModal() { this.showBookingForm = true; },
            closeModal() { this.showBookingForm = false; this.resetForm(); },
            
            fetchSchedules() {
                if (!this.selectedPoli) { this.schedules = []; return; }
                this.isLoading = true;
                this.schedules = [];
                
                fetch(`{{ url('/pengunjung/jadwal-by-poli') }}/${this.selectedPoli}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => { this.schedules = data; })
                    .catch(error => console.error('Error fetching schedules:', error))
                    .finally(() => { this.isLoading = false; });
            },
            
            formatDoctor(schedule) {
                const formattedCost = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(schedule.biaya_konsultasi);
                
                return `${schedule.gelar} ${schedule.nama_dokter} - ${formattedCost}`;
            },
    
            resetForm() {
                this.selectedPoli = '';
                this.schedules = [];
                this.isLoading = false;
            }
        }));
    });
</script>
@endsection
