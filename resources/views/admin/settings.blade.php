@extends('layouts.app')

@section('content')
<div class="p-6 bg-cream-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-cream-200 dark:border-gray-700 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase">
                Branding <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-amber-500">& Pengaturan</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic font-medium">
                Sesuaikan identitas visual dan nama aplikasi Stockify kamu di sini.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="max-w-2xl p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center animate-fade-in">
            <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi Error (JavaScript atau Backend) --}}
    <div id="js-error-alert" class="hidden max-w-2xl p-4 mb-6 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 flex items-center animate-shake">
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i> <span id="error-message"></span>
    </div>

    <div class="max-w-2xl bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-cream-200 dark:border-gray-700 overflow-hidden animate-fade-in">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-tighter">Identitas Aplikasi</h3>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="form-update-settings" class="p-8">
            @csrf
            <div class="space-y-8">
                {{-- Nama Aplikasi --}}
                <div class="group">
                    <label for="app_name" class="block mb-2 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-blue-600">Nama Aplikasi / Toko</label>
                    <input type="text" name="app_name" id="app_name" 
                        value="{{ $settings['app_name'] ?? 'Stockify' }}" 
                        class="block w-full p-4 text-sm font-bold text-gray-900 border border-cream-200 rounded-2xl bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-inner" 
                        placeholder="Contoh: Toko Surya Sejahtera" required>
                </div>

                {{-- Logo Aplikasi dengan Live Preview --}}
                <div class="group">
                    <label class="block mb-3 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest ml-1">Logo Aplikasi</label>
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-[2rem] border border-dashed border-gray-300 dark:border-gray-600">
                        
                        {{-- Preview Box --}}
                        <div class="relative w-24 h-24 flex-shrink-0 group/img">
                            <img id="logo-preview" 
                                src="{{ isset($settings['app_logo']) ? asset('storage/' . $settings['app_logo']) : 'https://ui-avatars.com/api/?name=S&background=random' }}" 
                                alt="Logo" class="w-full h-full rounded-2xl object-contain border-4 border-white dark:border-gray-800 bg-white shadow-md transition-transform group-hover/img:scale-105">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white dark:border-gray-800">
                                <i class="fas fa-camera text-xs"></i>
                            </div>
                        </div>
                        
                        <div class="flex-1 w-full text-center sm:text-left">
                            <input type="file" name="app_logo" id="logo-input" accept="image/png, image/jpeg, image/jpg" 
                                class="hidden">
                            <button type="button" onclick="document.getElementById('logo-input').click()" 
                                class="px-5 py-2.5 text-xs font-black text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 mb-2 uppercase">
                                Pilih File Logo
                            </button>
                            <p class="text-[10px] text-gray-400 font-medium italic">Format: PNG, JPG, JPEG. Maksimum ukuran file: 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 py-4 text-sm font-black text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all uppercase tracking-widest active:scale-95">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="confirmReset()" class="py-4 px-8 text-sm font-black text-red-500 bg-red-50 rounded-2xl hover:bg-red-600 hover:text-white transition-all uppercase tracking-widest active:scale-95 border border-red-100 dark:bg-red-900/20 dark:border-red-800">
                        <i class="fas fa-undo-alt mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </form>
        
        <form action="{{ route('admin.settings.reset') }}" method="POST" id="form-reset-settings" class="hidden">
            @csrf
        </form>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logo-input');
    const logoPreview = document.getElementById('logo-preview');
    const errorAlert = document.getElementById('js-error-alert');
    const errorMessage = document.getElementById('error-message');

    logoInput.onchange = evt => {
        const [file] = logoInput.files;
        if (file) {
            // 1. Validasi Tipe File
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                showError("Format file salah! Gunakan PNG atau JPG.");
                logoInput.value = "";
                return;
            }

            // 2. Validasi Ukuran File (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                showError("File terlalu besar! Maksimal adalah 2MB.");
                logoInput.value = "";
                return;
            }

            // Jika lolos validasi, tampilkan preview
            hideError();
            logoPreview.src = URL.createObjectURL(file);
        }
    }

    function showError(msg) {
        errorMessage.innerText = msg;
        errorAlert.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideError() {
        errorAlert.classList.add('hidden');
    }

    function confirmReset() {
        if(confirm('Apakah Anda yakin ingin mengembalikan ke pengaturan awal? Logo akan dihapus dan nama kembali ke Stockify.')) {
            document.getElementById('form-reset-settings').submit();
        }
    }
</script>

<style>
    @keyframes fade-in-up { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fade-in-up 0.5s ease-out; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }
</style>
@endsection