<x-app-layout>
    <div class="min-h-screen flex flex-col lg:flex-row justify-center">
        <div id="pixel-container" class="hidden lg:flex flex-col lg:w-1/2 items-center justify-center relative overflow-hidden bg-gradient-to-tr from-primary to-secondary">
            <x-fonts.sub-header class="absolute top-0 bg-primary text-text rounded-b-md w-full text-center z-20 h-[80px] flex items-center justify-center">
                {{ config('app.name') }}
            </x-fonts.sub-header>
            <x-application-logo />
        </div>
        <div class="w-full lg:w-1/2 flex items-center">
            <div class="w-full lg:w-4/5 m-4 lg:mx-auto lg:m-0">
                <x-container>
                    <div class="bg-secondary py-4 text-center fixed top-0 left-0 flex items-center justify-center space-x-4 w-full lg:hidden border-b-4 border-accent">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-md w-10 h-10" />
                        <x-fonts.sub-header class="flex items-center">
                            {{ config('app.name') }}
                        </x-fonts.sub-header>
                    </div>

                    <div class="bg-secondary flex pt-4 px-4 rounded-t-md border-b-4 border-accent gap-x-4 mt-16">
                        <button id="reset-tab" class="w-1/3 py-2 text-text bg-accent font-semibold rounded-t-md hover:bg-accent">
                            Reset Password
                        </button>
                    </div>

                    <div id="form-container" class="w-full px-4 py-6">
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="hidden">
                                <x-inputs.text
                                    class="w-full"
                                    type="email"
                                    name="email"
                                    :value="old('email', $request->email)"
                                    :messages="$errors->get('email')" />
                            </div>

                            <x-inputs.text
                                class="w-full"
                                type="password"
                                name="password"
                                :messages="$errors->get('password')" />

                            <x-inputs.text
                                class="w-full"
                                type="password"
                                name="password_confirmation"
                                :messages="$errors->get('password_confirmation')" />

                            <div class="flex items-center justify-center py-4">
                                <x-buttons.button type="edit" icon="fa-sync-alt" label="Reset" />
                            </div>
                        </form>
                        <x-copyright />
                    </div>
                </x-container>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pixelContainer = document.getElementById('pixel-container');
        const pixelCount = 40;
        const colors = ['#FAFAFA', '#545454'];

        for (let i = 0; i < pixelCount; i++) {
            const pixel = document.createElement('div');
            pixel.className = 'absolute opacity-40 animate-rise';

            const size = Math.random() * 6 + 2;
            pixel.style.width = `${size}px`;
            pixel.style.height = `${size}px`;

            const left = Math.random() * 100;
            const bottom = Math.random() * 50;

            const delay = (Math.random() * 0.5).toFixed(2);
            const duration = Math.floor(Math.random() * 3) + 3;
            const color = colors[Math.floor(Math.random() * colors.length)];
            pixel.style.backgroundColor = color;
            pixel.style.left = `${left}%`;
            pixel.style.bottom = `-${bottom}px`;
            pixel.style.animationDelay = `${delay}s`;
            pixel.style.animationDuration = `${duration}s`;
            pixelContainer.appendChild(pixel);
        }
    });
</script>