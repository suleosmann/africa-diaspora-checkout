<template>
  <div class="relative min-h-[70vh] bg-[#f0e9d7] py-12">
    <div style="background-image: url('/belowheader.png');" class="absolute top-0 left-0 right-0 h-16 bg-repeat-x"></div>
    
    <!-- Background Pattern - Right Side -->
    <div class="fixed top-0 right-0 w-1/2 h-full pointer-events-none z-0 overflow-hidden">
        <img 
            :src="`/bg2.png`" 
            alt="" 
            class="absolute -right-[0.05%] top-1/2 -translate-y-1/2 w-[300px] h-auto opacity-10"
        >
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-8 mt-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-start">
        
        <!-- Left Side - Video & Corridor Links -->
        <div class="space-y-6">
          <!-- Video -->
          <div class="rounded-2xl overflow-hidden shadow-xl">
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

          <!-- Corridor Links -->
          <div class="space-y-4">
            <p class="text-xl md:text-2xl font-medium italic text-gray-900">The corridor links:</p>
            <ul class="space-y-2 text-base md:text-lg text-gray-800 pl-2">
              <li class="flex items-start">
                <span class="mr-3">•</span>
                <span><span class="font-medium">Lamu (Kenya)</span> — Eastern anchor</span>
              </li>
              <li class="flex items-start">
                <span class="mr-3">•</span>
                <span><span class="font-medium">Juba</span> (South Sudan)</span>
              </li>
              <li class="flex items-start">
                <span class="mr-3">•</span>
                <span><span class="font-medium">Bambari — Bangui</span> (Central African Republic)</span>
              </li>
              <li class="flex items-start">
                <span class="mr-3">•</span>
                <span><span class="font-medium">Yaoundé — Douala</span> (Cameroon) — Western anchor</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Right Side - Text Content -->
        <div class="flex flex-col space-y-6 lg:space-y-8 font-medium">
          <!-- First Paragraph -->
          <p class="text-base md:text-lg lg:text-xl text-gray-900 leading-relaxed">
            Afrifund Capital, through Aden, serves as the strategic gateway connecting investors to credible information, vetted opportunities, and meaningful participation in the "Let's Build Africa" investment movement.
          </p>

          <!-- Second Paragraph -->
          <p class="text-base md:text-lg lg:text-xl text-gray-900 leading-relaxed">
            The Great Equatorial Land Bridge (GELB) is an African Union—endorsed mega-initiative designed to create a continuous multi-modal transport and energy corridor connecting the Indian Ocean to the Atlantic Ocean. Africa's Most Transformative Maritime, Logistics & Eco-Urban Investment Platform in line with AU Agenda 2063. This it to unlocking trade, investment, and regional integration, while stimulating urbanisation, industrialisation, knowledge economy development, and job creation along the route.
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
//
</script>