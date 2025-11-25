<template>
  <div class="relative  bg-cover bg-center bg-no-repeat -mt-16 " 
       style="background-image: url('/globe.avif');">
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>
    
    <!-- Your content goes here -->
    <div class="relative z-10">
      <div class="pt-12">
        <Navbar/>
      </div>
      
      <!-- Hero Section -->
      <div class="flex flex-col items-center justify-center text-center px-6 mt-16 mb-28">
        <h1 class="text-4xl md:text-6xl font-bold text-[#FFDA9E] mb-4 max-w-5xl">
          Unlocking Africa’s Growth Corridor
        </h1>
        
        <p class="text-white text-lg md:text-xl mb-8 max-w-5xl">
          Connecting talent, technology, and opportunity across the continent's fastest-growing digital ecosystem
        </p>
        
        <a 
          href="/register"
          class="inline-block bg-[#3D2817] hover:bg-[#2a1d13] text-[#FFDA9E] font-semibold px-24 py-4 rounded-2xl text-lg transition-all border-2 border-[#FFDA9E]"
        >
          Join Today
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
import FutureSection
 from '../Components/FutureSection.vue';
const downloadThesis = () => {
  const link = document.createElement('a')
  link.href = '/thesis.pdf'
  link.download = 'ADEN-Thesis.pdf'
  link.click()
}
</script>