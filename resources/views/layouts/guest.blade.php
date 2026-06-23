<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FOTOKOPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
    <div class="flex min-h-screen">
        
        <!-- 1. BRANDING AREA (Now on the LEFT) -->
        <div class="hidden lg:flex flex-1 bg-slate-900 items-center justify-center p-12">
            <div class="text-center">
                <img src="{{ asset('images/LOGO_FOTOKOPI.jpeg') }}" 
                    alt="Logo" 
                    class="w-96 h-48 rounded-xl mx-auto mb-8 shadow-2xl object-contain bg-white">
                <h1 class="text-white text-4xl font-black mb-4">FOTOKOPI</h1>
                <p class="text-slate-400">Smart Inventory Control</p>
            </div>
        </div>

        <!-- 2. LOGIN FORM AREA (Now on the RIGHT) -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24">
            <!-- Increased width classes here -->
        <div class="mx-auto w-full max-w-md lg:w-[450px]">
        
        {{ $slot }}
        
    </div>
</div>

    </div>
</body>
</html>