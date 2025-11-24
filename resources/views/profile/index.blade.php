<x-layouts.app>
    <section class="bg-gray-50 min-h-[calc(100dvh-130px)] py-6">
        {{-- Container --}}
        <div class="container mx-auto flex flex-col lg:flex-row gap-x-6">
            {{-- Sidebar --}}
            <div class="min-w-[22%] hidden lg:flex">
                <x-profile-sidebar />
            </div>

            {{-- Content --}}
            <div class="flex-1 space-y-6">
                {{-- Personal Information --}}
                <div class="border rounded-lg w-full">
                    <p class="text-xl text-primary font-semibbold p-6 border-b">Informasi Pribadi</p>

                    <form 
                        action={{ route('profile.update') }}
                        method="POST"
                        class="flex flex-col gap-6 p-6"
                    >
                        @csrf

                        {{-- Name --}}
                        <div class="grid gap-2">
                            <x-ui.label for="name" :required="true">Nama</x-ui.label>
                            <x-ui.input
                                id="name"
                                type="text"
                                required
                                value="{{ auth()->user()->name }}"
                                tabIndex="1"
                                autoComplete="name"
                                name="name"
                                placeholder="Nama"
                            />
                        </div>

                        {{-- Email --}}
                        <div class="grid gap-2">
                            <x-ui.label for="email">Email</x-ui.label>
                            <x-ui.input
                                id="email"
                                type="email"
                                value="{{ auth()->user()->email }}"
                                disabled
                                name="email"
                                class="cursor-not-allowed"
                            />
                        </div>

                        {{-- Submit Button --}}
                        <x-ui.button 
                            type="submit" 
                            class="mt-2 font-semibold self-end px-8" 
                            tabIndex="2">
                            Ubah
                        </x-ui.button>
                    </form>
                </div>

                {{-- Password --}}
                <div class="border rounded-lg w-full">
                    <p class="text-xl text-primary font-semibbold p-6 border-b">Ubah Password</p>

                    <form 
                        action={{ route('profile.update-password') }}
                        method="POST"
                        class="flex flex-col gap-6 p-6"
                    >
                        @csrf

                        {{-- Old Password --}}
                        <div class="grid gap-2">
                            <x-ui.label for="old_password" :required="true">Password Lama</x-ui.label>
                            <x-ui.input
                                id="old_password"
                                type="password"
                                required
                                tabIndex="3"
                                autoComplete="password"
                                name="old_password"
                                placeholder="Password"
                            />
                        </div>

                        {{-- New Password --}}
                        <div class="grid gap-2">
                            <x-ui.label for="password">Password Baru</x-ui.label>
                            <x-ui.input
                                id="password"
                                type="password"
                                tabindex="4"
                                name="password"
                                placeholder="Password baru"
                            />
                        </div>

                        {{-- New Password Confirmation --}}
                        <div class="grid gap-2">
                            <x-ui.label for="password_confirmation">Konfirmasi Password Baru</x-ui.label>
                            <x-ui.input
                                id="password_confirmation"
                                type="password"
                                tabindex="5"
                                name="password_confirmation"
                                placeholder="Password baru"
                            />
                        </div>

                        {{-- Submit Button --}}
                        <x-ui.button 
                            type="submit" 
                            class="mt-2 font-semibold self-end px-8" 
                            tabIndex="6">
                            Ubah
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
