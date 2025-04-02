<x-app-layout>
    <div class="min-h-screen flex flex-col lg:flex-row justify-center">
        <div class="hidden lg:flex flex-col lg:w-1/2 bg-secondary items-center justify-center">
            <x-application-logo class="fill-current rounded-md"  />
        </div>
        <div class="w-full lg:w-1/2 flex items-center">
            <div class="w-full lg:w-4/5 mx-auto">
                <x-container>
                    <div class="bg-secondary py-4 text-center fixed top-0 left-0 w-full lg:hidden border-b-4 border-accent">
                        <x-fonts.sub-header>Digital Portal</x-fonts.sub-header>
                    </div>

                    <div class="bg-secondary flex pt-4 px-4 rounded-t-md border-b-4 border-accent gap-x-4 mt-16">
                        <button id="login-tab" onclick="toggleForms('login')" class="w-1/3 py-2 text-text bg-accent font-semibold rounded-t-md hover:bg-accent">
                            Login
                        </button>
                        <button id="register-tab" onclick="toggleForms('register')" class="w-1/3 py-2 text-text bg-primary hover:bg-accent font-semibold rounded-t-md">
                            Register
                        </button>
                        <button id="forgot-tab" onclick="showForm('forgot-form'); setActiveTab('forgot')" class="w-1/3 py-2 text-text bg-primary hover:bg-accent font-semibold rounded-t-md">
                            Forgot Password?
                        </button>
                    </div>

                    <div id="form-container" class="w-full px-4 py-6">
                        <div id="login-form" class="form block">
                            @include('auth.login')
                        </div>
                        <div id="register-form" class="form hidden">
                            @include('auth.register')
                        </div>
                        <div id="forgot-form" class="form hidden">
                            @include('auth.forgot-password')
                        </div>

                        <x-copyright />
                    </div>
                </x-container>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    function toggleForms(formType) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const forgotForm = document.getElementById('forgot-form');

        forgotForm.classList.add('hidden');

        if (formType == 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            setActiveTab('login');
        } else if (formType == 'register') {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            setActiveTab('register');
        }
    }

    function setActiveTab(activeTab) {
        const tabButtons = document.querySelectorAll('.bg-secondary button');
        tabButtons.forEach(btn => {
            btn.classList.remove('bg-accent');
            btn.classList.add('bg-primary');
        });
        if (activeTab == 'login') {
            document.getElementById('login-tab').classList.remove('bg-primary');
            document.getElementById('login-tab').classList.add('bg-accent');
        } else if (activeTab == 'register') {
            document.getElementById('register-tab').classList.remove('bg-primary');
            document.getElementById('register-tab').classList.add('bg-accent');
        } else if (activeTab == 'forgot') {
            document.getElementById('forgot-tab').classList.remove('bg-primary');
            document.getElementById('forgot-tab').classList.add('bg-accent');
        }
    }

    function showForm(formId) {
        document.querySelectorAll('.form').forEach(form => form.classList.add('hidden'));
        const selectedForm = document.getElementById(formId);
        selectedForm.classList.remove('hidden');
    }
</script>