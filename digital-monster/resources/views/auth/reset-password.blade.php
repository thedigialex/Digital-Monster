<x-app-layout>
    <div class="min-h-screen flex flex-col lg:flex-row justify-center">
        <div class="hidden lg:flex flex-col lg:w-1/2 bg-secondary items-center justify-center">
            <x-application-logo class="fill-current" width="400" height="400" />
        </div>
        <div class="w-full lg:w-1/2 flex items-center">
            <div class="w-full lg:w-4/5 mx-auto">
                <x-container>
                    <div class="bg-secondary py-4 text-center fixed top-0 left-0 w-full lg:hidden border-b-4 border-accent">
                        <x-fonts.sub-header>Digital Portal</x-fonts.sub-header>
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
                                <x-buttons.primary icon="fa-sync-alt" label="Reset Password" />
                            </div>
                        </form>
                        <x-copyright />
                    </div>
                </x-container>
            </div>
        </div>
    </div>
</x-app-layout>