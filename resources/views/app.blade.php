<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>Africa Diaspora Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @inertiaHead
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 relative min-h-screen overflow-x-hidden">
    <!-- Background Pattern - Left Side -->
    <div class="fixed top-0 left-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            src="/bg1.png" 
            alt="" 
            class="absolute  top-1/2 -translate-y-1/2 w-[300px] h-auto opacity-10"
        >
    </div>
    
    <!-- Background Pattern - Right Side -->
    <div class="fixed top-0 right-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            src="/bg2.png" 
            alt="" 
            class="absolute -right-[0.05%] top-1/2 -translate-y-1/2 w-[300px] h-auto opacity-10"
        >
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10">
        @inertia
    </div>
</body>
</html>