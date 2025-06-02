<x-app-layout>
    <div class="min-h-screen flex lg:flex-row">
        <div class="w-full lg:w-1/2 bg-primary m-2 md:m-8 rounded-lg shadow-lg shadow-secondary overflow-hidden">
            <div class="flex flex-col gap-8 items-center justify-center">
                <div class="w-full bg-gradient-to-tr from-primary to-tertiary py-4 flex items-center justify-center gap-4 border-b-4 border-accent">
                    <i class="fa-solid fa-server text-accent text-4xl"></i>
                    <x-fonts.sub-header>
                        {{ config('app.name') }}
                    </x-fonts.sub-header>
                </div>
                <div class="flex flex-col justify-center md:w-4/5">
                    <div class="flex border-b-4 border-accent gap-4">
                        <x-buttons.tab id="login-tab" onclick="toggleForms('login-form', 'login-tab')" class="w-1/3 bg-secondary text-text" label="Login" />
                        <x-buttons.tab id="register-tab" onclick="toggleForms('register-form', 'register-tab')" class="w-1/3 bg-secondary text-text" label="Register" />
                        <x-buttons.tab id="forgot-tab" onclick="toggleForms('forgot-form', 'forgot-tab')" class="w-1/3 bg-secondary text-text" label="Password?" />
                    </div>
                    <div class="py-4">
                        @include('auth.login')
                        @include('auth.register')
                        @include('auth.forgot-password')
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex w-1/2 items-center justify-center">
            <x-application-logo class="shadow-lg shadow-secondary" />
        </div>
    </div>
</x-app-layout>

<script>
    let forms, tabs;

    function setup() {
        forms = [
            document.getElementById('login-form'),
            document.getElementById('register-form'),
            document.getElementById('forgot-form')
        ];
        tabs = [
            document.getElementById('login-tab'),
            document.getElementById('register-tab'),
            document.getElementById('forgot-tab')
        ];
        toggleForms('login-form', 'login-tab');
    }

    function toggleForms(formId, tabId) {
        forms.forEach(form => {
            if (form.id == formId) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        });
        tabs.forEach(tab => {
            if (tab.id === tabId) {
                tab.classList.remove('text-text', 'bg-secondary');
                tab.classList.add('text-secondary', 'bg-accent');
            } else {
                tab.classList.remove('text-secondary', 'bg-accent');
                tab.classList.add('text-text', 'bg-secondary');
            }
        });
    }
    setup();
</script>