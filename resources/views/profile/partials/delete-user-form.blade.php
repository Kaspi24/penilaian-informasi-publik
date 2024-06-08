<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Setelah akun Anda dihapus, seluruh data termasuk jawaban kuesioner yang tersimpan akan dihapus secara permanen.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Anda yakin ingin menghapus akun Anda?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Setelah akun Anda dihapus, seluruh data termasuk jawaban kuesioner yang tersimpan akan dihapus secara permanen. Harap masukkan Password untuk melanjutkan penghapusan akun.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Password"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
