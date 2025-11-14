<template>
  <div class="relative min-h-[80vh] bg-cover bg-center bg-no-repeat -mt-8" 
       style="background-image: url('/globe.avif');">
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>
    
    <!-- Your content goes here -->
    <div class="relative z-10">
      <div class="pt-12">
        <Navbar/>
      </div>
      
      <!-- Hero Section -->
      <div class="flex flex-col items-center justify-center text-center px-6 mt-32">
        <h1 class="text-5xl md:text-6xl font-bold text-[#FFDA9E] mb-6 max-w-5xl">
          Begin your Journey to Own Africa
        </h1>
        
        <p class="text-white text-lg md:text-xl mb-24 max-w-5xl">
          Connecting talent, technology, and opportunity across the continent's fastest-growing digital ecosystem
        </p>
        
        <a 
          href="/download-thesis"
          class="inline-block bg-[#3D2817] hover:bg-[#2a1d13] text-[#FFDA9E] font-semibold px-24 py-4 rounded-2xl text-lg transition-all border-2 border-[#FFDA9E]"
        >
          Download the ADEN Thesis
        </a>
      </div>
    </div>
    <!-- Video Section Component -->
     <div class="mt-24">
      <VideoSection/>
     </div>
     <div>
      <JoinSection/>
     </div>
     <div>
      <InfoSection/>
     </div>
     
    
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import VideoSection from '@/Components/VideoSection.vue'
import InfoSection from '../Components/InfoSection.vue';
import JoinSection from '../Components/JoinSection.vue';
const downloadThesis = () => {
  const link = document.createElement('a')
  link.href = '/thesis.pdf'
  link.download = 'ADEN-Thesis.pdf'
  link.click()
}
</script>