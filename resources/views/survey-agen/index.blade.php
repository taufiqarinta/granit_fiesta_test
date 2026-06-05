<x-app-layout>
            <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight">
                        {{ __('Daftar Form Survey') }}
            </h2>
            <a href="{{ route('form-survey.scan-qr') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg flex items-center gap-2 transition">
                Scan QR
            </a>
        </div>
    </x-slot>

    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('form-survey.index') }}" class="mb-6">
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Kode Survey, Kode Agen, atau Nama Agen</label>
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ $search ?? '' }}"
                                    placeholder="Masukkan kata kunci pencarian"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600"
                                >
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                                    Cari
                                </button>
                                @if(!empty($search))
                                    <a href="{{ route('form-survey.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-5 py-2.5 rounded-lg transition">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    
                    @if($surveys->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kode Survey</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kode Agen</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Agen</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Sales</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Status Klaim</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($surveys as $survey)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $survey->kode_survey }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $survey->kode_agen }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $survey->nama_agen }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ optional($survey->details->first())->nama_sales ?? '-' }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($survey->status_klaim_hadiah == 1)
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Sudah
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Belum
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('form-survey.detail', $survey->kode_survey) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $surveys->links() }}
                        </div>
                    @elseif(!empty($search))
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l6 6m-2-11a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Data tidak ditemukan</h3>
                            <p class="text-gray-600 mb-4">Tidak ada survey yang cocok dengan pencarian "{{ $search }}".</p>
                            <a href="{{ route('form-survey.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                                Lihat Semua Data
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada survey agen</h3>
                            <p class="text-gray-600 mb-4">Mulai dengan membuat survey agen baru</p>
                            <a href="{{ route('form-survey.form') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                                Buat Survey Baru
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
