<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow rounded-lg">

        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- ❌ Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-200 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('setup.admin.store') }}" class="space-y-4">
            @csrf

            {{-- Admin Name --}}
            <div>
                <x-input-label for="name" :value="__('Admin Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Admin Email --}}
            <div>
                <x-input-label for="email" :value="__('Admin Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Password Confirmation --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
            </div>

            {{-- Submit Button --}}
            <div>
                <x-primary-button class="w-full">
                    {{ __('Create Admin') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
