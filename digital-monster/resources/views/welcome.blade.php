<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Gate</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased selection:bg-cyan-500 h-screen bg-primary">
    <div class="h-full flex flex-col lg:flex-row justify-center">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-2 lg:p-32">
            <div class="bg-secondary rounded-lg justify-center">
                <!-- Tab navigation -->
                <div class="flex justify-center space-x-4 bg-accent pt-4 rounded-t-md">
                    <button id="login-tab" onclick="toggleForms('login')" class="w-64 py-2 text-text bg-secondary font-semibold rounded-t-md hover:bg-primary ">
                        Login
                    </button>
                    <button id="register-tab" onclick="toggleForms('register')" class="w-64 py-2 text-text bg-primary hover:bg-primary font-semibold rounded-t-md">
                        Register
                    </button>
                    <button id="forgot-tab" onclick="showForm('forgot-form'); setActiveTab('forgot')" class="w-64 py-2 text-text bg-primary hover:bg-primary  font-semibold rounded-t-md">
                        Forgot Password?
                    </button>
                </div>

                <!-- Forms container -->
                <div id="form-container" class="w-full p-2 lg:p-4">
                    <div id="login-form" class="form block">
                        @include('auth.login')
                    </div>
                    <div id="register-form" class="form hidden">
                        @include('auth.register')
                    </div>
                    <div id="forgot-form" class="form hidden">
                        @include('auth.forgot-password')
                    </div>

                    <div class=" py-2 text-center ">
                        <x-fonts.paragraph><span class="text-accent"> &copy; TheDigiAlex 2024</span></x-fonts.paragraph>
                    </div>
                </div>
            </div>

        </div>
        <div class="stars-overlay"></div>
        <div class="hidden lg:flex flex-col lg:w-1/2 bg-secondary items-center justify-center">
            <x-application-logo class="fill-current" width="400" height="400" />
        </div>
    </div>

    <script>
        function toggleForms(formType) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const forgotForm = document.getElementById('forgot-form');

            // Hide the forgot form
            forgotForm.classList.add('hidden');

            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                setActiveTab('login');
            } else if (formType === 'register') {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                setActiveTab('register');
            }
        }

        function setActiveTab(activeTab) {
            // Remove bg-accent from all tabs
            document.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-secondary');
                btn.classList.add('bg-primary');
            });

            // Add bg-accent to the active tab
            if (activeTab === 'login') {
                document.getElementById('login-tab').classList.add('bg-secondary');
            } else if (activeTab === 'register') {
                document.getElementById('register-tab').classList.add('bg-secondary');
            } else if (activeTab === 'forgot') {
                document.getElementById('forgot-tab').classList.add('bg-secondary');
            }
        }

        function showForm(formId) {
            // Hide all forms
            document.querySelectorAll('.form').forEach(form => form.classList.add('hidden'));

            // Show the selected form
            const selectedForm = document.getElementById(formId);
            selectedForm.classList.remove('hidden');
        }
    </script>

</body>

</html>