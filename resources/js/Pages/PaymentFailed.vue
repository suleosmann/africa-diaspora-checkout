<template>
  <div class="min-h-screen flex items-center justify-center bg-red-50 p-6">
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-md text-center">
      <h1 class="text-2xl font-semibold text-red-700 mb-4">
        Payment Failed ❌
      </h1>

      <p class="text-gray-700 mb-4">
        {{ message || 'Your payment could not be completed.' }}
      </p>

      <div class="bg-red-100 rounded-md py-3 mb-6 text-sm text-red-800">
        Please try again or contact support if the issue persists.
      </div>

      <a
        href="/"
        class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md transition"
      >
        Back to Contribution Page
      </a>
    </div>
  </div>
</template>

<script setup>
defineProps({
  message: String,
})
</script>
