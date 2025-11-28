<template>
  <div class="hero-section relative bg-no-repeat -mt-16 w-full" 
     style="background-image: url('/bg13.jpg');">
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>
    
    <!-- Your content goes here -->
    <div class="relative z-10">
      <div class="pt-12">
        <Navbar/>
      </div>
      
      <!-- Hero Section -->
      <div class="flex flex-col items-center justify-center text-center px-4 sm:px-6 mt-16 mb-28">
        <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold text-[#FFDA9E] mb-4 max-w-5xl">
          Let's Build Africa
        </h1>
        
        <p class="text-white text-base sm:text-lg md:text-xl mb-8 max-w-5xl">
          Connecting talent, technology, and opportunity across the continent's fastest-growing digital ecosystem
        </p>
        
        <a 
          href="/register"
          class="inline-block bg-[#3D2817] hover:bg-[#2a1d13] text-[#FFDA9E] font-semibold px-12 sm:px-24 py-4 rounded-2xl text-base sm:text-lg transition-all border-2 border-[#FFDA9E]"
        >
          Let's Build Africa | Join ADEN Today
        </a>
      </div>
      
    </div>
  
    <!-- Video Section Component -->
     <div class="">
      <VideoSection/>
     </div>
     <div>
      <JoinSection/>
     </div>
     <div>
      <WhyThisMatters/>
     </div>
     <div>
      <InfoSection/>
     </div>
     <div>
      <FutureSection/>
     </div>
      
     
    
  </div>
  <div>
        <Footer/> 
      </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import VideoSection from '@/Components/VideoSection.vue'
import InfoSection from '../Components/InfoSection.vue';
import JoinSection from '../Components/JoinSection.vue';
import WhyThisMatters from '../Components/WhyThisMatters.vue';
import Footer from '../Components/Footer.vue';
import FutureSection from '../Components/FutureSection.vue';

const downloadThesis = () => {
  const link = document.createElement('a')
  link.href = '/thesis.pdf'
  link.download = 'ADEN-Thesis.pdf'
  link.click()
}
</script>

<style scoped>
.hero-section {
  background-size: cover;
  background-position: center;
}

@media (min-width: 640px) {
  .hero-section {
    background-size: 100% auto;
    background-position: top center;
  }
}
</style>