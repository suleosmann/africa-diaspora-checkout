<template>
  <div class="relative min-h-[70vh] bg-[#F5F1E8]">
    <div style="background-image: url('/belowheader.png');" class="mt-24 h-20"></div>
    <!-- Background Pattern - Right Side -->
    <div class="fixed top-0 right-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            :src="`/bg2.png`" 
            alt="" 
            class="absolute -right-[0.05%] top-1/2 -translate-y-1/2 w-[300px] h-auto opacity-10"
        >
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-20">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <!-- Left Side - Video -->
        <div>
          <p class="text-gray-900 text-lg mb-8 leading-relaxed">
            If you are new to the African investment Landscape and want to learn from 
            credible experts and content creators. Watch the video below
          </p>
          
          <div class="rounded-3xl overflow-hidden shadow-2xl">
            <iframe 
              width="100%" 
              height="400" 
              src="https://www.youtube.com/embed/wbsbYT484yo" 
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen
              class="w-full aspect-video"
            ></iframe>
          </div>
        </div>

        <!-- Right Side - Text -->
        <div class="flex items-center justify-center lg:justify-end">
          <h2 class="text-2xl md:text-4xl font-semibold text-gray-900 leading-tight italic">
            Are you ready for 'real time' updates and access to Africa's largest-scale infrastructure projects and investment opportunities?
          </h2>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
//
</script>