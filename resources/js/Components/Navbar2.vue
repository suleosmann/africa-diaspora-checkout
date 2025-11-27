<template>
    <nav class="backdrop-blur-xl bg-[#3D2817]/80 py-6 px-6 rounded-2xl max-w-6xl mx-auto mt-6 border border-[#FFDA9E]/20 shadow-2xl">
        <div class="flex items-center justify-between">
            <!-- Logo Section -->
            <div class="flex items-center gap-3">
                <a href="/">
                <img 
                    :src="logoUrl" 
                    alt="ADEN Logo" 
                    class="h-10 w-auto drop-shadow-lg"
                >
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center gap-8 mr-6 font-semibold">
                <Link 
                    href="/" 
                    class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
                >
                    About Us
                </Link>
                <a 
                    href="/register"
                    class="text-[#3D2817] bg-[#FFDA9E]/90 backdrop-blur-sm transition-all text-sm border-2 border-[#FFDA9E] px-6 py-2 rounded-lg hover:bg-[#FFDA9E] hover:scale-105 font-bold shadow-lg"
                >
                    Join Today
                </a>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const logoUrl = '/navbar-logo.png';

const isActive = (path) => {
    return page.url === path;
};
</script>

<style scoped>
nav {
    box-shadow: 
        0 8px 32px 0 rgba(0, 0, 0, 0.37),
        inset 0 1px 0 0 rgba(255, 218, 158, 0.1);
    background: linear-gradient(
        135deg,
        rgba(61, 40, 23, 0.9) 0%,
        rgba(61, 40, 23, 0.7) 100%
    );
}
</style>