<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NFTWTLSDQL"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-NFTWTLSDQL');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.paystack.co/v1/inline.js" defer></script>
    <link rel="icon" type="image/png" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">


    <title inertia>Aden Africa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @inertiaHead
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 relative min-h-screen overflow-x-hidden">
    <!-- Background Pattern - Left Side -->
    <div class="fixed top-0 left-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            src="/bg1.png" 
            alt="" 
            class="absolute top-1/2 -translate-y-1/2 w-[300px] max-w-[80vw] h-auto opacity-10"
        >
    </div>
    
    <!-- Background Pattern - Right Side -->
    <div class="fixed top-0 right-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            src="/bg2.png" 
            alt="" 
            class="absolute right-0 top-1/2 -translate-y-1/2 w-[300px] max-w-[80vw] h-auto opacity-10"
        >
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10 overflow-x-hidden">
        @inertia
    </div>
</body>
</html>