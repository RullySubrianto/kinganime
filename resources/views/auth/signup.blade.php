<x-layouts.app contentClass="items-center justify-center bg-gray-50 min-h-[calc(100dvh-130px)]">
    <section class="container flex justify-center">
        <div class="w-lg bg-white rounded-lg px-4 md:px-10 py-10 flex flex-col gap-8">
            {{-- Title --}}
            <div class="flex flex-col items-center gap-4">
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-semibold">Daftar Akun Baru</h1>
                </div>
            </div>

            <form 
                action={{ route('register.store') }} 
                method="POST"
                class="flex flex-col gap-6">
                @csrf

                <div class="grid gap-6">
                    {{-- Name --}}
                    <div class="grid gap-2">
                        <x-ui.label for="name" :required="true">Username</x-ui.label>

                        <x-ui.input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            class="bg-white!"
                            placeholder="Nama"
                            autocomplete="username"
                            tabindex="1"/>

                        <x-error-inline-input :value="'username'"/>
                    </div>

                    {{-- Email --}}
                    <div class="grid gap-2">
                        <x-ui.label for="email" :required="true">Email</x-ui.label>

                        <x-ui.input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="bg-white!"
                            placeholder="john@gmail.com"
                            autocomplete="email"
                            tabindex="2"/>

                        <x-error-inline-input :value="'email'"/>
                    </div>

                    {{-- Password --}}
                    <div class="grid gap-2">
                        <x-ui.label for="password" :required="true">Password</x-ui.label>

                        <x-ui.input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="bg-white!"
                            placeholder="Password"
                            autocomplete="password"
                            tabindex="3"/>
                        
                        <x-error-inline-input :value="'password'"/>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="grid gap-2">
                        <x-ui.label for="password_confirmation" :required="true">Konfirmasi Password</x-ui.label>

                        <x-ui.input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="bg-white!"
                            placeholder="Konfirmasi Password"
                            autocomplete="password"
                            tabindex="4"/>
                        
                        <x-error-inline-input :value="'password_confirmation'"/>
                    </div>

                    {{-- Submit Button --}}
                    <x-ui.button
                        type="submit"
                        class="mt-2 w-full"
                        tabindex="5">
                        Daftar
                    </x-ui.button>
                </div>

            </form>
        </div>
    </section>
</x-layouts.app>
