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
                <a 
                    href="/download-thesis"
                    class="text-[#FFDA9E] transition-colors text-sm"
                >
                    Download Thesis
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>