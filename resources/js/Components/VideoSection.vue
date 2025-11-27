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
      <!-- Video floated left -->
      <div class="float-left mr-8 mb-6 w-full md:w-[600px]">
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
      </div>

      <!-- Text Content wrapping around video -->
      <div class="space-y-6 text-base md:text-lg text-gray-900 leading-relaxed">
        <!-- First Paragraph -->
        <p>
          Afri Fund Capital, through Aden, serves as the strategic gateway connecting investors, changemakers and visionaries to credible information, vetted opportunities, and meaningful participation in the "Let's Build Africa" investment movement.
        </p>

        <!-- Second Paragraph -->
        <p>
          The Great Equatorial Land Bridge (GELB) is a visionary and ambitious multi-national infrastructure and economic development corridor proposed to span Central Africa, connecting the Atlantic and Indian Oceans. It represents one of the most significant and transformative development projects conceived for the African continent.
        </p>

        <!-- Third Paragraph -->
        <p>
          More than just a transport route, the GELB is envisioned as a comprehensive "development corridor" designed to unlock the immense economic potential of the African heartland, foster unprecedented regional integration, and create a sustainable, self-sufficient economic ecosystem.
        </p>

        <!-- Fourth Paragraph -->
        <p>
          The core justification for the project stems from the need to overcome one of Africa's most profound historical challenges: the lack of high-capacity, reliable east-west infrastructure.
        </p>

        <!-- Fifth Paragraph -->
        <p>
          By creating this land bridge, the project aims to dramatically reduce transport costs and times, link landlocked countries to global markets, stimulate industrialization and value-addition, and serve as a powerful catalyst for peace, stability, and shared prosperity across the continent.
        </p>

        <!-- Corridor Links -->
        <div class="clear-left pt-4">
          <p class="text-xl font-medium italic text-gray-900 mb-3">The corridor links:</p>
          <ul class="space-y-2 text-gray-800 pl-2">
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
    </div>
  </div>
</template>

<script setup>
//
</script>