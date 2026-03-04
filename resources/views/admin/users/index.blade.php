@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Otoritas <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Pengguna</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Kelola hak akses dan identitas akun Admin, Manajer, serta Staff Gudang Stockify.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search Bar --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full sm:w-64 p-2.5 pl-10 text-sm text-gray-900 border border-cream-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm" 
                    placeholder="Cari nama atau email...">
            </form>

            <button type="button" data-modal-target="add-user-modal" data-modal-toggle="add-user-modal" 
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <i class="fas fa-user-plus mr-2"></i>Tambah
            </button>
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-fixed">
                <thead class="text-[11px] font-black uppercase tracking-widest text-gray-400 bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-5 w-[250px]">Identitas Pengguna</th>
                        <th class="px-6 py-5 w-[150px]">Hak Akses</th>
                        <th class="px-6 py-5 w-[180px]">Tanggal Join</th>
                        <th class="px-6 py-5 w-[200px] text-center">Manajemen Akun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="group hover:bg-indigo-50/20 dark:hover:bg-indigo-900/10 transition-all duration-200">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar Placeholder --}}
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-blue-50 dark:from-indigo-900/40 dark:to-gray-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm shadow-inner group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, " "), 1, 1)) ?: '' }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-base font-black text-gray-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $user->name }}</span>
                                        <span class="text-[11px] font-medium text-gray-400 lowercase">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-lg uppercase tracking-widest
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 
                                       ($user->role == 'manager' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 
                                       'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300') }}">
                                    <i class="fas {{ $user->role == 'admin' ? 'fa-shield-alt' : ($user->role == 'manager' ? 'fa-user-tie' : 'fa-box') }} mr-1.5 text-[8px]"></i>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400 italic">
                                    <i class="far fa-calendar-alt mr-1.5 text-indigo-400"></i>{{ $user->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center items-center gap-2">
                                    @if(auth()->id() !== $user->id) {{-- Tidak bisa hapus diri sendiri --}}
                                        <button type="button" data-modal-target="edit-user-modal-{{ $user->id }}" data-modal-toggle="edit-user-modal-{{ $user->id }}" 
                                            class="inline-flex items-center px-4 py-2 text-[10px] font-black text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 shadow-sm">
                                            <i class="fas fa-user-edit mr-1.5"></i> EDIT
                                        </button>

                                        <button type="button" data-modal-target="delete-user-modal-{{ $user->id }}" data-modal-toggle="delete-user-modal-{{ $user->id }}" 
                                            class="inline-flex items-center px-4 py-2 text-[10px] font-black text-red-700 bg-red-50 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:bg-red-900/20 dark:border-red-800 shadow-sm">
                                            <i class="fas fa-user-minus mr-1.5"></i> HAPUS
                                        </button>
                                    @else
                                        <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 dark:bg-indigo-900/30 dark:border-indigo-800">AKUN ANDA</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @include('admin.users.modals.edit', ['user' => $user])
                        @include('admin.users.modals.delete', ['user' => $user])

                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 italic font-medium">Data pengguna tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 px-4">
        {{ $users->appends(['search' => request('search')])->links() }}
    </div>
</div>

@include('admin.users.modals.create')

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.5s ease-out; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

@endsection