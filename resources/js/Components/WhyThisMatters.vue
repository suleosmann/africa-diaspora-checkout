<template>
  <div class="relative min-h-[60vh] py-16" style="background-image: url('/bg11.jpg'); background-size: cover; background-position: center;">
    <!-- White Overlay -->
    <div class="absolute inset-0 bg-white/80 z-0"></div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6">
      <!-- Title -->
      <h2 class="text-4xl md:text-5xl font-bold text-[#3D2817] text-center mb-12">
        Why This Matters
      </h2>

      <!-- Tabs Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side - Tab Buttons -->
        <div class="lg:col-span-4 space-y-4">
          <button
            v-for="(tab, index) in tabs"
            :key="index"
            @click="activeTab = index"
            :class="[
              'w-full text-left px-8 py-4 rounded-xl font-medium text-lg transition-all',
              activeTab === index 
                ? 'bg-[#FFDA9E] text-[#3D2817] border-2 border-[#3D2817]' 
                : 'bg-white text-[#3D2817] border-2 border-transparent hover:border-[#FFDA9E]'
            ]"
          >
            {{ tab.title }}
          </button>
        </div>

        <!-- Right Side - Content Area -->
        <div class="lg:col-span-8">
          <div class="bg-[#FFDA9E] rounded-2xl p-8 md:p-10 border-2 border-[#3D2817] min-h-[500px]">
            <ul class="space-y-6 text-[#3D2817] text-base md:text-lg">
              <li 
                v-for="(point, index) in tabs[activeTab].content" 
                :key="index"
                class="flex items-start"
              >
                <span class="mr-3 mt-1 flex-shrink-0">•</span>
                <span class="leading-relaxed">{{ point }}</span>
              </li>
            </ul>

            <!-- CTA Button (if available) -->
            <div v-if="tabs[activeTab].showCTA" class="mt-8">
              <a 
                href="/register"
                class="inline-block bg-[#3D2817] hover:bg-[#2a1d13] text-[#FFDA9E] font-semibold px-8 py-3 rounded-xl transition-all border-2 border-[#3D2817]"
              >
                Join ADEN Today
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref(0)

const tabs = [
  {
    title: 'Africa Is Entering a High-Growth Era',
    content: [
      "Africa's population will reach 2.5 billion by 2050, representing 25% of the world's consumers and workforce, creating unmatched demand for logistics, housing, energy, manufacturing, and transport.",
      "Sub-Saharan Africa GDP is projected to grow from 4% (2024) to 4.2% (2026) already outpacing global averages and shifting capital flows toward the continent.",
      "Through AfCFTA, Africa is now the largest free trade area on earth, unifying 55 countries and a $3.4 trillion GDP market, removing barriers and enabling seamless cross-border supply chains.",
      "Intra-African trade remains only 16.6%, compared to 68% in Europe showing vast untapped market potential for corridors like GELB to unlock.",
      "Transformational initiatives like AfCFTA and GELB can lift 30 million people out of extreme poverty and increase African income by $450 billion by 2035."
    ],
    showCTA: false
  },
  {
    title: 'The Infrastructure and Investment Imperative',
    content: [
      "Africa faces a $2 trillion infrastructure gap by 2033, representing one of the largest investment opportunities globally—with logistics, ports, transport, SEZs, and power leading demand.",
      "Bridging this gap could increase GDP growth by up to 2% annually, significantly expanding returns for early-mover investors.",
      "Global capital is already flowing: the African Union and partners have launched a $1 trillion private investment framework to accelerate continental infrastructure.",
      "Economic corridors like GELB are proven high-multiplier assets—unlocking agriculture, mining, logistics, energy, and industrial value chains simultaneously."
    ],
    showCTA: false
  },
  {
    title: 'Strategic Purpose of GELB',
    content: [
      "Integrate East, Central, and West Africa through a seamless road–rail–energy network",
      "Reduce trans-African cargo movement from 21 days → 6–8 days",
      "Unlock intra-African trade under AFCFTA (expected to rise from 18% → 50%+ by 2040)",
      "Enable value-added manufacturing, mineral exports, and agro-industrial supply chains",
      "Create Africa's largest continental trade spine"
    ],
    showCTA: false
  },
  {
    title: 'Why Lamu Port SEZ City Is the Entry Point',
    content: [
      "Lamu Port SEZ City, being one of the major components of the eastern anchor of GELB, is envisioned to transform Kenya's northern coastline into a world-class maritime, industrial, and eco-urban hub, combining port operations, special economic zones (SEZs), logistics networks, and hospitality infrastructure.",
      "This development will also anchor the LAPSSET Corridor, positioning Lamu as a regional trade gateway linking East Africa to global markets.",
      "Lamu Port SEZ City is a premier transshipment, manufacturing, and logistics centre in Africa, driving regional integration, sustainable industrialization, and blue economy growth.",
      "Positions Kenya as a premier gateway to East, Central, and West Africa.",
      "Integrates port, logistics, rail, SEZ, and eco-city development.",
      "Offers multi-sector investment opportunities with high returns backed by continental demand."
    ],
    showCTA: true
  }
]
</script>