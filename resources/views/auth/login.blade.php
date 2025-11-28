<x-layouts.app contentClass="items-center justify-center bg-gray-50 dark:bg-background min-h-[calc(100dvh-130px)]">
    <section class="container flex justify-center">
        <div class="w-lg bg-white dark:bg-background dark:border rounded-lg px-4 md:px-10 py-10 flex flex-col gap-8">
            {{-- Title --}}
            <div class="flex flex-col items-center gap-4">
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-semibold">Masuk ke akun anda</h1>
                </div>
            </div>

            <form 
                action={{ route('login.store') }} 
                method="POST"
                class="flex flex-col gap-6"
                x-data="{
                    email: '',
                    password: '',
                    get ready() {
                        return (
                            this.email.trim().length > 0 &&
                            this.password.trim().length >= 8
                        );
                    }
                }">
                @csrf

                <div class="grid gap-6">
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
                            tabindex="1"
                            x-model="email"/>
                    </div>

                    {{-- Password --}}
                    <div class="grid gap-2">
                        <x-ui.label for="password" :required="true">Password</x-ui.label>

                        <x-ui.input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class=""
                            placeholder="Password"
                            autocomplete="password"
                            tabindex="2"
                            x-model="password"/>
                    </div>

                    <div>
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
                            tabindex="3"
                            x-bind:disabled="!ready"
                            x-bind:class="!ready ? 'opacity-50 cursor-not-allowed' : ''">
                            Masuk
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app>
