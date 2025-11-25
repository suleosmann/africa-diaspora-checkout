<template>
      <Navbar/>
  <div class="min-h-screen  flex items-center justify-center -mt-20 p-6">
    <div class="max-w-2xl w-full">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-[#3D2817] mb-4">
          Welcome to the ADEN Network!
        </h1>

        <!-- Message -->
        <p class="text-lg text-gray-700 mb-8 leading-relaxed">
          Thank you for joining the Africa & Diaspora Economic Network. You now have access to our community and resources. Check your email for confirmation and next steps.
        </p>

        <!-- What's Next Section -->
        <div class="bg-[#FFDA9E]/20 rounded-xl p-6 mb-8 text-left">
          <h2 class="text-xl font-bold text-[#3D2817] mb-4">What's Next?</h2>
          <ul class="space-y-3 text-gray-800">
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">✓</span>
              <span>Check your email for a confirmation message</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">✓</span>
              <span>Explore investment opportunities on our platform</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">✓</span>
              <span>Connect with other members in the network</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">✓</span>
              <span>Stay updated on Africa's growth corridor initiatives</span>
            </li>
          </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a 
            href="/" 
            class="inline-block bg-[#3D2817] hover:bg-[#2a1d13] text-[#FFDA9E] font-semibold px-8 py-3 rounded-xl transition-all"
          >
            Back to Home
          </a>
          <!-- <a 
            href="/register" 
            class="inline-block bg-white hover:bg-gray-50 text-[#3D2817] font-semibold px-8 py-3 rounded-xl transition-all border-2 border-[#3D2817]"
          >
            Upgrade Membership
          </a> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
//
import Navbar from '@/Components/Navbar.vue'

</script>