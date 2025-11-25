<template>
      <Navbar/>
  <div class="min-h-screen  flex items-center justify-center -mt-10">
    <div class="max-w-2xl w-full">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
          <div class="w-20 h-20 bg-[#FFDA9E] rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-[#3D2817]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-[#3D2817] mb-">
          Thank You for Becoming a Member!
        </h1>

        <!-- Message -->
        <p class="text-lg text-gray-700 mb-8 leading-relaxed">
          Your membership has been confirmed. Your download will start automatically. If it doesn't start, click the button below.
        </p>

        <!-- Download Section -->
        <div class="bg-[#3D2817] rounded-xl p-6 mb-">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-[#FFDA9E] rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#3D2817]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div class="text-left">
                <h3 class="text-lg font-bold text-[#FFDA9E]">ADEN Investment Thesis</h3>
                <p class="text-sm text-[#FFDA9E]/80">PDF Document</p>
              </div>
            </div>
          </div>
          
          <button
            @click="downloadThesis"
            class="w-full bg-[#FFDA9E] hover:bg-[#ffd180] text-[#3D2817] font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download Thesis
          </button>
        </div>

        <!-- Additional Info -->
        <div class="bg-[#FFDA9E]/20 rounded-xl p-6 mb-8 text-left">
          <h2 class="text-xl font-bold text-[#3D2817] mb-4">What's Included:</h2>
          <ul class="space-y-3 text-gray-800">
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">•</span>
              <span>Comprehensive overview of the GELB corridor initiative</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">•</span>
              <span>Investment opportunities in African infrastructure</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">•</span>
              <span>Economic impact analysis and projections</span>
            </li>
            <li class="flex items-start">
              <span class="mr-3 text-[#3D2817]">•</span>
              <span>Strategic partnerships and collaboration opportunities</span>
            </li>
          </ul>
        </div>

        <!-- Note -->
        <p class="text-sm text-gray-600">
          A confirmation email has been sent to your inbox with additional resources.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import { onMounted } from 'vue'

// Auto download on page load
onMounted(() => {
  downloadThesis()
})

const downloadThesis = () => {
  const link = document.createElement('a')
  link.href = '/download-thesis'
  link.download = 'ADEN-Investment-Thesis.pdf'
  link.click()
}
</script>