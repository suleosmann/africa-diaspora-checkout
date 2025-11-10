<template>
    <nav class="bg-[#3D2817] py-6 px-6 rounded-2xl max-w-6xl mx-auto mt-6">
        <div class="flex items-center justify-between">
            <!-- Logo Section -->
            <div class="flex items-center gap-3">
                <img 
                    :src="logoUrl" 
                    alt="ADEN Logo" 
                    class="h-10 w-auto"
                >
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center gap-8 mr-6 font-semibold">
                <Link 
                    href="/about" 
                    class="text-[#FFDA9E] transition-colors text-sm"
                >
                    About Us
                </Link>
                <Link 
                    href="/download-thesis" 
                    class="text-[#FFDA9E] transition-colors text-sm"
                >
                    Download Thesis
                </Link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

// Public folder assets are served directly at the root
const logoUrl = '/navbar-logo.png';

// Check if the current route is active
const isActive = (path) => {
    return page.url === path;
};
</script>

<style scoped>
nav {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>