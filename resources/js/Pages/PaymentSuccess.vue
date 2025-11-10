<template>
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class=" shadow-xl rounded-2xl p-8 max-w-md text-center relative z-10">
      <div class="mb-6">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Payment Successful!
        </h1>
      </div>

      <p class="text-lg text-gray-700 mb-2">
        Thank you, <span class="font-semibold text-gray-900">{{ data.customer.first_name ?? 'Contributor' }}</span>
      </p>
      <p class="text-gray-600 mb-8">
        Your contribution has been received successfully.
      </p>

      <!-- Payment Details Card -->
      <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-8 text-left">
        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
          <span class="text-sm text-gray-600">Reference:</span>
          <span class="text-sm font-mono font-medium text-gray-900">{{ data.reference }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-600">Amount:</span>
          <span class="text-2xl font-bold text-green-600">
            {{ data.currency }} {{ (data.amount / 100).toFixed(2) }}
          </span>
        </div>
      </div>

        <a      
        href="/"
        class="inline-block w-full bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md"
      >
        Make Another Contribution </a>
      
      <p class="text-xs text-gray-500 mt-6">
        A confirmation email has been sent to your registered email address.
      </p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  data: Object,
})
</script>