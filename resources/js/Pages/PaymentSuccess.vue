<template>
  <Navbar/>
  <div class="min-h-screen  flex items-center justify-center -mt-20 p-6">

    <div class="w-full max-w-md bg-[#FFDA9E] shadow-xl border border-[#3D2817] rounded-2xl p-8 text-center relative z-10">

      <!-- Success Icon -->
      <div class="mb-6">
        <div class="w-20 h-20 bg-[#FFDA9E] border border-[#3D2817] rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-[#3D2817]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>

        <h1 class="text-3xl font-bold text-[#3D2817] mb-2">
          Payment Successful!
        </h1>
      </div>

      <p class="text-lg text-gray-800 mb-2">
        Thank you, <span class="font-semibold text-[#3D2817]">{{ data.customer.first_name ?? 'Contributor' }}</span>
      </p>

      <p class="text-gray-700 mb-8">
        Your membership payment has been processed successfully.
      </p>

      <!-- Payment Details -->
      <div class="bg-[#FFFAF3] border border-[#3D2817] rounded-lg p-5 mb-8 text-left">

        <div class="flex justify-between items-center mb-4 pb-3 border-b border-[#3D2817]/30">
          <span class="text-sm text-[#3D2817]/70">Reference:</span>
          <span class="text-sm font-mono font-medium text-[#3D2817]">{{ data.reference }}</span>
        </div>

        <div class="flex justify-between items-center">
          <span class="text-sm text-[#3D2817]/70">Amount Paid:</span>
          <span class="text-2xl font-bold text-[#3D2817]">
            {{ data.currency }} {{ (data.amount / 100).toFixed(2) }}
          </span>
        </div>

      </div>

      <!-- Button -->
      <a 
        href="/"
        class="inline-block w-full bg-[#3D2817] hover:bg-[#2C1D12] text-white font-semibold px-6 py-3 rounded-lg transition"
      >
        Return Home
      </a>

      <p class="text-xs text-gray-600 mt-6">
        A confirmation email has been sent to your registered address.
      </p>

    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'

defineProps({
  data: Object,
})
</script>
