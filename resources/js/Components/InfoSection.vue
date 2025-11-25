<template>
  <div class="relative min-h-screen py-16" style="background-image: url('/bg5.png'); background-size: cover; background-position: center;">
    <!-- Brown Overlay -->
    <div class="absolute inset-0 bg-[#3D2817]/98 z-0"></div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6">
      <!-- Title -->
      <h2 class="text-4xl md:text-5xl font-bold text-[#FFDA9E] mb-12">
          Core Components<br/>
          (Global-Investor Ready)
      </h2>

      <!-- Tabs Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side - Tab Buttons -->
        <div class="lg:col-span-5 flex flex-col">
          <!-- Tab 1 - Different styling with description inside -->
          <button
            @click="activeTab = 0"
            :class="[
              'text-left p-6 rounded-xl font-medium text-lg transition-all  mb-4',
              activeTab === 0 
                ? 'bg-[#5A3D2B] text-[#FFDA9E]' 
                : 'bg-transparent text-[#FFDA9E]  border-[#FFDA9E] hover:bg-[#5A3D2B]'
            ]"
          >
            <div class="font-bold mb-2">1. Cargo Handling Infrastructure</div>
            <p class="text-sm leading-relaxed">
              Africa's port demand is growing 5–7% annually, driven by AFCFTA, manufacturing expansion, and logistics modernization. This is the fastest growth rate globally after Southeast Asia.
            </p>
          </button>

          <!-- Tab 2 -->
          <button
            @click="activeTab = 1"
            :class="[
              'text-left p-6 rounded-xl font-medium text-lg transition-all  mb-4',
              activeTab === 1 
                ? 'bg-[#5A3D2B] text-[#FFDA9E]' 
                : 'bg-transparent text-[#FFDA9E] border-[#FFDA9E] hover:bg-[#5A3D2B]'
            ]"
          >
            <div class="font-bold mb-2">2. Ship Services</div>
            <p class="text-sm leading-relaxed">
              Marine services cut vessel downtime by 40–60%, directly boosting port competitiveness.
            </p>
          </button>

          <!-- Tab 3 -->
          <button
            @click="activeTab = 2"
            :class="[
              'text-left p-6 rounded-xl font-medium text-lg transition-all  mb-4',
              activeTab === 2
                ? 'bg-[#5A3D2B] text-[#FFDA9E]' 
                : 'bg-transparent text-[#FFDA9E] border-[#FFDA9E] hover:bg-[#5A3D2B]'
            ]"
          >
            <div class="font-bold mb-2">3. Logistics & Hinterland Connectivity</div>
            <p class="text-sm leading-relaxed">
              Integrated logistics reduces transport cost from 40% to 15–20% of product value.
            </p>
          </button>

          <!-- Tab 4 -->
          <button
            @click="activeTab = 3"
            :class="[
              'text-left p-6 rounded-xl font-medium text-lg transition-all  mb-4',
              activeTab === 3
                ? 'bg-[#5A3D2B] text-[#FFDA9E]' 
                : 'bg-transparent text-[#FFDA9E] border-[#FFDA9E] hover:bg-[#5A3D2B]'
            ]"
          >
            <div class="font-bold mb-2">4. Power Supply (Shore Power)</div>
            <p class="text-sm leading-relaxed">
              Emission reduction: 60–70% during berth. Shore power is a new African frontier, growing demand + green financing + carbon credit revenue streams.
            </p>
          </button>

          <!-- Tab 5 -->
          <button
            @click="activeTab = 4"
            :class="[
              'text-left p-6 rounded-xl font-medium text-lg transition-all',
              activeTab === 4
                ? 'bg-[#5A3D2B] text-[#FFDA9E]' 
                : 'bg-transparent text-[#FFDA9E] border-[#FFDA9E] hover:bg-[#5A3D2B]'
            ]"
          >
            <div class="font-bold mb-2">5. Hospitality & Residential Eco-City</div>
            <p class="text-sm leading-relaxed">
              Creates a future-ready live-work-play waterfront city supporting tourism, staff, and international investors.
            </p>
          </button>
        </div>

        <!-- Right Side - Detailed Content -->
        <div class="lg:col-span-7 flex flex-col">
          <div class="bg-[#F5F1E8] rounded-2xl p-8 md:p-10 overflow-y-auto flex-1">
            
            <!-- Cargo Handling Infrastructure Content -->
            <div v-if="activeTab === 0" class="space-y-8">
              <!-- Container Terminal -->
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Container Terminal</h3>
                <p class="text-[#3D2817] leading-relaxed mb-2">
                  <span class="font-semibold">What it is:</span> STS cranes, RTGs, yard equipment, container stacks. 
                  <span class="font-semibold">Capacity:</span> 0.8M–2.0M TEU/year based on berth configuration this will reduce vessel dwell time from 7–12 days to &lt;3 days.
                </p>
                <p class="text-[#3D2817] leading-relaxed">
                  Africa will see container volumes double by 2035, creating a $15B gap in terminal capacity. Investors entering now secure first-mover advantage in markets with high margins, low competition, and strong state support.
                </p>
              </div>

              <!-- Bulk Terminals -->
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Bulk Terminals (Dry + Liquid)</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Dry Bulk Capacity: 3–10 million tons/year. Liquid Bulk Storage: 50,000–300,000 m³. Africa's mineral exports (copper, cobalt, bauxite, fertilizer inputs) are expanding at 8–12% CAGR. Bulk terminals deliver USD returns + USD denominated long-term contracts with miners and energy companies.
                </p>
              </div>

              <!-- General Cargo / Ro-Ro -->
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">General Cargo / Ro-Ro</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Handles vehicles, heavy machinery, project cargo. Throughput: 50,000–300,000 vehicles/year. Africa's automotive imports will hit 6–8 million vehicles/year by 2030. Ro-Ro concessions provide 20–30 year cash-generating assets, insulated from commodity fluctuations.
                </p>
              </div>

              <!-- Warehousing & Cold Storage -->
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Warehousing & Cold Storage</h3>
                <p class="text-[#3D2817] leading-relaxed mb-3"><span class="font-semibold">Capacity:</span></p>
                <ul class="space-y-2 text-[#3D2817] ml-6 mb-3">
                  <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Cold Storage: 10,000–30,000 pallet positions</span>
                  </li>
                  <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Dry Warehousing: 50,000–150,000 sqm</span>
                  </li>
                </ul>
                <p class="text-[#3D2817] leading-relaxed">
                  Perishable exports (flowers, fruits, veg) are Africa's fastest growing export class—cold chain yields 15–25% IRR with guaranteed off-take agreements.
                </p>
              </div>
            </div>

            <!-- Ship Services Content -->
            <div v-if="activeTab === 1" class="space-y-6">
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Pilotage & Towage</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Handles vessels up to 200,000 DWT. Towage operations have stable annuity-style revenues, often > USD 30,000 per vessel call. High margins, predictable, recession-proof.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Mooring Facilities</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Optimized for 40-knot wind tolerance. Low capital cost, high demand. Easiest entry point for global investors seeking quick returns and low operational risk.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Dry Docking & Ship Repair</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Africa loses billions as 70% of vessels go outside the continent for repair. A dry dock is a near-monopoly asset in most African regions. IRR often exceeds 20–30% with long-term anchoring contracts from shipping lines.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Green Fueling (LNG, Methanol, Hydrogen)</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Aligns with IMO emission targets. Only three African ports have LNG bunkering. Investors gain first-mover advantage in future fuels, positioning for the $1 trillion global green-shipping transition.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Ballast Water Treatment</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Ensures marine ecosystem safety. Mandatory by IMO, creates guaranteed demand with regulatory protection and minimal competition.
                </p>
              </div>
            </div>

            <!-- Logistics & Hinterland Connectivity Content -->
            <div v-if="activeTab === 2" class="space-y-6">
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Rail, Roads & ICDs</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Rail moves 10–25 million tons/year. Africa's rail modernization is a $100B investment wave. High-impact, multilateral-supported, long-term revenue from haulage concessions.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">SEZs & Free Trade Zones</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Industrial clusters near the port. Companies inside SEZs export at 2–3x higher volumes. Investors capture rental income, utilities revenue, and industrial services, a diversified revenue ecosystem.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Digital Customs / One-Stop Shop</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Reduces clearance time from 5–10 days → 24–48 hours. Customs digitalization is the highest ROI public-private investment, low capex, high efficiency, long-term concession fees.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Truck Bays & Intermodal Hubs</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Capacity: 500–2,000 trucks/day. Generates immediate cashflow (parking, weighbridge, scanning fees). Demand is chronic and growing, guaranteed profitability.
                </p>
              </div>
            </div>

            <!-- Power Supply Content -->
            <div v-if="activeTab === 3" class="space-y-6">
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Shore Power</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Emission reduction: 60–70% during berth. Shore power is a new African frontier, growing demand + green financing + carbon credit revenue streams.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Fresh Water & Waste Reception</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Utility concessions generate predictable USD-denominated income from every vessel call.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">ICT, Port Community System (PCS)</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Reduces paperwork 30–50%, speeds cargo flow 20–30%. Digital ports have 2x higher throughput. Software + concession = scalable, high-margin business, low OPEX, long-term recurring revenue.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Insurance, Banking, Financial Services</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Enables real-time trade financing. With AFCFTA, Africa's trade finance gap hits $80–100 billion. Ports become natural financial hubs, banking returns with infrastructure stability.
                </p>
              </div>
            </div>

            <!-- Hospitality & Residential Eco-City Content -->
            <div v-if="activeTab === 4" class="space-y-6">
              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Hotels (5-Star, 4-Star, 3-Star)</h3>
                <ul class="space-y-2 text-[#3D2817] ml-6 mb-3">
                  <li>• 5-Star: 500 rooms</li>
                  <li>• 4-Star: 350 rooms</li>
                  <li>• 3-Star: 250 rooms</li>
                </ul>
                <p class="text-[#3D2817] leading-relaxed">
                  Waterfront hospitality in Africa commands 30–55% higher RevPAR than city hotels. Cruise tourism growing 12% annually, major upside.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Cruise Ship Terminal</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  Handles 2 liners simultaneously. Africa receives 600,000–800,000 cruise tourists/year, expected to double by 2030. Cruise terminals drive spending multipliers of 1:5 in the local economy.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Sailing Club & Marina</h3>
                <p class="text-[#3D2817] leading-relaxed">
                  200 boats; sailing school + clubhouse. Marinas deliver the highest real-estate ROI in waterfront developments globally.
                </p>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-[#3D2817] mb-4">Residential Ecosystem</h3>
                <ul class="space-y-2 text-[#3D2817] ml-6 mb-3">
                  <li>• 5,000 staff housing units</li>
                  <li>• 150 villas</li>
                  <li>• 200-bed hospital</li>
                  <li>• 1,500-student school & vocational centre</li>
                </ul>
                <p class="text-[#3D2817] leading-relaxed">
                  Integrated living reduces staff churn by 40% and attracts international firms seeking operational bases.
                </p>
              </div>
            </div>

          </div>
        </div>

      </div>

      <!-- CTA Button -->
      <div class="text-center mt-12">
        <a 
          href="/register"
          class="inline-block bg-[#FFDA9E] hover:bg-[#ffd180] text-[#3D2817] font-bold px-24 py-4 rounded-lg text-lg transition-all"
        >
          JOIN ADEN TODAY
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref(0)
</script>