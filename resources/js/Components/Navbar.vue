<template>
    <nav class="backdrop-blur-xl bg-white/10 py-4 sm:py-6 px-4 sm:px-6 rounded-xl sm:rounded-2xl max-w-6xl mx-auto mt-4 sm:mt-6 border border-white/20 shadow-2xl">
        <div class="flex items-center justify-between">
            <!-- Logo Section -->
            <div class="flex items-center gap-3">
                <a href="/">
                <img 
                    :src="logoUrl" 
                    alt="ADEN Logo" 
                    class="h-8 sm:h-10 w-auto drop-shadow-lg"
                >
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="isMenuOpen = !isMenuOpen"
                class="md:hidden text-[#FFDA9E] p-2"
                aria-label="Toggle menu"
            >
                <svg v-if="!isMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-6 lg:gap-8 mr-6 font-semibold">
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

        <!-- Mobile Navigation Menu -->
        <div 
            v-show="isMenuOpen"
            class="md:hidden mt-4 pt-4 border-t border-white/20 flex flex-col gap-4 font-semibold"
        >
            <Link 
                href="/" 
                @click="isMenuOpen = false"
                class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
            >
                About Us
            </Link>
            <a 
                href="/register"
                @click="isMenuOpen = false"
                class="text-[#3D2817] bg-[#FFDA9E]/90 backdrop-blur-sm transition-all text-sm border-2 border-[#FFDA9E] px-6 py-2 rounded-lg hover:bg-[#FFDA9E] font-bold shadow-lg text-center"
            >
                Join Today
            </a>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const logoUrl = '/navbar-logo.png';
const isMenuOpen = ref(false);

const isActive = (path) => {
    return page.url === path;
};
</script>

<style scoped>
nav {
    box-shadow: 
        0 8px 32px 0 rgba(0, 0, 0, 0.37),
        inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
    background: linear-gradient(
        135deg,
        rgba(61, 40, 23, 0.15) 0%,
        rgba(61, 40, 23, 0.1) 100%
    );
}
</style>