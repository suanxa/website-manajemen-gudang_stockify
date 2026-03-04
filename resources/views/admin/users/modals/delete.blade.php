<div id="delete-user-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
    <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?</h3>
                
                @if(auth()->id() == $user->id)
                    <p class="mb-4 text-sm text-red-600 font-bold">Peringatan: Anda tidak bisa menghapus akun Anda sendiri!</p>
                    <button data-modal-toggle="delete-user-modal-{{ $user->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                        Batal
                    </button>
                @else
                    <div class="flex justify-center gap-3">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Hapus
                            </button>
                        </form>
                        <button data-modal-toggle="delete-user-modal-{{ $user->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                            Batal
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>