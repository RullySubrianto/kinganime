<x-layouts.app contentClass="items-center justify-center bg-gray-50 dark:bg-background min-h-[calc(100dvh-123px)]">
    <section class="container flex justify-center">
        <div class="w-lg bg-white dark:bg-background dark:border rounded-lg px-4 md:px-10 py-10 flex flex-col gap-8">
            {{-- Title --}}
            <div class="flex flex-col items-center gap-4">
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-semibold">Daftar Akun Baru</h1>
                </div>
            </div>

            <form 
                action={{ route('register.store') }} 
                method="POST"
                class="flex flex-col gap-6"
                x-data="{
                    username: '',
                    email: '',
                    password: '',
                    confirm: '',

                    get validPassword() {
                        return (
                            this.password.length >= 8 &&
                            /[a-z]/.test(this.password) &&
                            /[A-Z]/.test(this.password) &&
                            /[0-9]/.test(this.password)
                        );
                    },

                    get confirmed() {
                        return this.confirm === this.password && this.password.length > 0;
                    },

                    get allValid() {
                        return (
                            this.username.trim().length > 0 &&
                            this.email.trim().length > 0 &&
                            this.validPassword &&
                            this.confirmed
                        );
                    }
                }">

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
                            class=""
                            placeholder="Nama"
                            autocomplete="username"
                            tabindex="1"
                            x-model="username"/>

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
                            class=""
                            placeholder="john@gmail.com"
                            autocomplete="email"
                            tabindex="2"
                            x-model="email"/>

                        <x-error-inline-input :value="'email'"/>
                    </div>

                    {{-- Password --}}
                    <div class="grid gap-2">
                        <x-ui.label for="password" :required="true">Password</x-ui.label>

                        <div
                            class="relative" 
                            x-data="{ show: false }">
                            <x-ui.input
                                x-bind:type="show ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                class=""
                                placeholder="Password"
                                autocomplete="password"
                                tabindex="3"
                                x-model="password"/>

                            {{-- Toggle --}}
                            <button
                                type="button"
                                class="absolute top-1.5 right-2.5 p-1 cursor-pointer"
                                x-on:click="show = !show"
                            >
                                <span x-cloak x-show="!show">
                                    <x-icon name="eye-password" class="w-4 h-4" />
                                </span>
                                <span x-cloak x-show="show">
                                    <x-icon name="eye-slash-password" class="w-4 h-4" />
                                </span>
                            </button>
                        </div>

                        {{-- Validation --}}
                        <div class="mt-1 space-y-1 text-sm">
                            {{-- Minimum Length --}}
                            <p class="flex items-center gap-2"
                                :class="{
                                    'text-green-600': password.length >= 8,
                                    'text-gray-500': password.length < 8
                                }"
                            >
                                <x-icon 
                                    name="check"
                                    :class="{
                                        'text-green-600': password.length >= 8,
                                        'text-gray-400': password.length < 8
                                    }"
                                    class="w-4 h-4"
                                />
                                Minimal 8 karakter
                            </p>

                            {{-- Contain Uppercase & Lowercase --}}
                            <p class="flex items-center gap-2"
                                :class="{
                                    'text-green-600': /[a-z]/.test(password) && /[A-Z]/.test(password),
                                    'text-gray-500': !( /[a-z]/.test(password) && /[A-Z]/.test(password) )
                                }"
                            >
                                <x-icon 
                                    name="check"
                                    :class="{
                                        'bg-green-600': /[a-z]/.test(password) && /[A-Z]/.test(password),
                                        'bg-gray-400': !( /[a-z]/.test(password) && /[A-Z]/.test(password) )
                                    }"
                                    class="w-4 h-4"
                                />
                                Huruf besar & kecil
                            </p>

                            {{-- Contain Number --}}
                            <p class="flex items-center gap-2"
                                :class="{
                                    'text-green-600': /[0-9]/.test(password),
                                    'text-gray-500': !/[0-9]/.test(password)
                                }"
                            >
                                <x-icon 
                                    name="check"
                                    :class="{
                                        'text-green-600': /[0-9]/.test(password),
                                        'text-gray-500': !/[0-9]/.test(password)
                                    }"
                                    class="w-4 h-4"
                                />

                                Minimal mengandung 1 angka
                            </p>

                        </div>
                        
                        <x-error-inline-input :value="'password'"/>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="grid gap-2">
                        <x-ui.label for="password_confirmation" :required="true">Konfirmasi Password</x-ui.label>

                        <div
                            class="relative" 
                            x-data="{ showConfirm: false }">
                            <x-ui.input
                                x-bind:type="showConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                class=""
                                placeholder="Konfirmasi Password"
                                autocomplete="password"
                                tabindex="4"
                                x-model="confirm"/>
                            
                            {{-- Toggle --}}
                            <button
                                type="button"
                                class="absolute top-1.5 right-2.5 p-1 cursor-pointer"
                                x-on:click="showConfirm = !showConfirm"
                            >
                                <span x-cloak x-show="!showConfirm">
                                    <x-icon name="eye-password" class="w-4 h-4" />
                                </span>
                                <span x-cloak x-show="showConfirm">
                                    <x-icon name="eye-slash-password" class="w-4 h-4" />
                                </span>
                            </button>
                        </div>

                        <p class="flex items-center gap-2 mt-1 text-sm"
                            :class="{
                                'text-green-600': confirmed,
                                'text-gray-500': !confirmed
                            }"
                        >
                            <x-icon 
                                name="check"
                                :class="{
                                    'text-green-600': confirmed,
                                    'text-gray-500': !confirmed
                                }"
                                class="w-4 h-4"
                            />
                            Konfirmasi password harus sama
                        </p>

                        
                        <x-error-inline-input :value="'password_confirmation'"/>
                    </div>

                    <div>
                        {{-- Validation Error Message --}}
                        @if ($errors->any())
                            <ul class="mb-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-medium my-1 text-red-600">{{ $error }}</li>  
                                @endforeach    
                            </ul>                
                        @endif

                        {{-- Submit Button --}}
                        <x-ui.button
                            type="submit"
                            class="mt-2 w-full"
                            tabindex="5"
                            x-bind:disabled="!allValid"
                            x-bind:class="!allValid ? 'opacity-50 cursor-not-allowed' : ''">
                            Daftar
                        </x-ui.button>

                        <p class="text-center mt-4 text-sm ">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}" class="underline text-primary dark:text-white hover:text-primry/90 dark:hover:text-gray-200">Masuk</a>
                        </p>
                    </div>
                </div>

            </form>
        </div>
    </section>
</x-layouts.app>
