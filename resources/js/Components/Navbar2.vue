<template>
    <nav class="backdrop-blur-xl bg-[#3D2817]/80 py-4 sm:py-6 px-4 sm:px-6 rounded-xl sm:rounded-2xl max-w-6xl mx-auto mt-4 sm:mt-6 border border-[#FFDA9E]/20 shadow-2xl">
        <div class="flex items-center justify-between">
            <!-- Logo Section -->
            <div class="flex items-center gap-3">
                <a href="https://aden.africa/">
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
                <a 
                    href="https://aden.africa/" 
                    class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
                >
                    Home
                </a>
                
                <!-- What is ADEN? Dropdown -->
                <div class="relative group">
                    <button 
                        class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md flex items-center gap-1"
                    >
                        What is ADEN?
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="backdrop-blur-xl bg-[#3D2817]/95 rounded-lg border border-[#FFDA9E]/20 shadow-xl py-2">
                            <a 
                                href="https://aden.africa/our-vehicle-afri-fund-capital/" 
                                class="block px-4 py-2 text-[#FFDA9E] hover:text-white hover:bg-[#FFDA9E]/10 transition-all text-sm"
                            >
                                Our Vehicle: Afri Fund Capital
                            </a>
                        </div>
                    </div>
                </div>

                <a 
                    href="https://membership.aden.africa" 
                    class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
                >
                    Membership
                </a>

                <a 
                    href="https://aden.africa/insights/" 
                    class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
                >
                    Insights
                </a>

                <!-- About Us Dropdown -->
                <div class="relative group">
                    <button 
                        class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md flex items-center gap-1"
                    >
                        About Us
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="backdrop-blur-xl bg-[#3D2817]/95 rounded-lg border border-[#FFDA9E]/20 shadow-xl py-2">
                            <a 
                                href="https://aden.africa/about-us/" 
                                class="block px-4 py-2 text-[#FFDA9E] hover:text-white hover:bg-[#FFDA9E]/10 transition-all text-sm"
                            >
                                Who We Are
                            </a>
                            <a 
                                href="https://aden.africa/our-leadership/" 
                                class="block px-4 py-2 text-[#FFDA9E] hover:text-white hover:bg-[#FFDA9E]/10 transition-all text-sm"
                            >
                                Leadership
                            </a>
                            <a 
                                href="https://aden.africa/contact/" 
                                class="block px-4 py-2 text-[#FFDA9E] hover:text-white hover:bg-[#FFDA9E]/10 transition-all text-sm"
                            >
                                Contact
                            </a>
                        </div>
                    </div>
                </div>

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
            class="md:hidden mt-4 pt-4 border-t border-[#FFDA9E]/20 flex flex-col gap-4 font-semibold"
        >
            <a 
                href="https://aden.africa/" 
                @click="isMenuOpen = false"
                class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
            >
                Home
            </a>

            <!-- Mobile What is ADEN? -->
            <div>
                <button 
                    @click="isWhatIsAdenOpen = !isWhatIsAdenOpen"
                    class="w-full text-left text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md flex items-center justify-between"
                >
                    What is ADEN?
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isWhatIsAdenOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div v-show="isWhatIsAdenOpen" class="ml-4 mt-2 space-y-2">
                    <a 
                        href="https://aden.africa/our-vehicle-afri-fund-capital/" 
                        @click="isMenuOpen = false"
                        class="block text-[#FFDA9E]/80 transition-all text-sm hover:text-white"
                    >
                        Our Vehicle: Afri Fund Capital
                    </a>
                </div>
            </div>

            <a 
                href="https://membership.aden.africa" 
                @click="isMenuOpen = false"
                class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
            >
                Membership
            </a>

            <a 
                href="https://aden.africa/insights/" 
                @click="isMenuOpen = false"
                class="text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md"
            >
                Insights
            </a>

            <!-- Mobile About Us -->
            <div>
                <button 
                    @click="isAboutUsOpen = !isAboutUsOpen"
                    class="w-full text-left text-[#FFDA9E] transition-all text-sm hover:text-white drop-shadow-md flex items-center justify-between"
                >
                    About Us
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isAboutUsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div v-show="isAboutUsOpen" class="ml-4 mt-2 space-y-2">
                    <a 
                        href="https://aden.africa/about-us/" 
                        @click="isMenuOpen = false"
                        class="block text-[#FFDA9E]/80 transition-all text-sm hover:text-white"
                    >
                        Who We Are
                    </a>
                    <a 
                        href="https://aden.africa/our-leadership/" 
                        @click="isMenuOpen = false"
                        class="block text-[#FFDA9E]/80 transition-all text-sm hover:text-white"
                    >
                        Leadership
                    </a>
                    <a 
                        href="https://aden.africa/contact/" 
                        @click="isMenuOpen = false"
                        class="block text-[#FFDA9E]/80 transition-all text-sm hover:text-white"
                    >
                        Contact
                    </a>
                </div>
            </div>

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
import { ref } from 'vue';

const logoUrl = '/navbar-logo.png';
const isMenuOpen = ref(false);
const isWhatIsAdenOpen = ref(false);
const isAboutUsOpen = ref(false);
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