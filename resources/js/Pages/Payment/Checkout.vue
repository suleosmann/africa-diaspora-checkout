<template>
    <Navbar/>
  <div class="min-h-screen flex justify-center items-center -mt-16 p-6">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 border border-[#3D2817]">
      
      <h1 class="text-3xl font-bold text-[#3D2817] mb-2">
        Membership Payment
      </h1>
      <p class="text-gray-700 mb-6">
        Complete your payment to join <strong>Aden Africa</strong>.
      </p>

      <!-- Amount -->
      <div class="bg-[#FFDA9E] border border-[#3D2817] rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center">
          <span class="text-lg font-bold text-[#3D2817]">{{ membership }}</span>
          <span class="text-2xl font-bold text-[#3D2817]">${{ amount }}</span>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ errorMessage }}
      </div>

      <!-- Card Form -->
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-1">Card Number</label>
          <input v-model="card.number" type="text"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FFDA9E]" 
            placeholder="1234 5678 9012 3456"
            :disabled="processing">
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">MM</label>
            <input v-model="card.month" type="text" class="w-full border rounded-lg p-3" 
              placeholder="12" maxlength="2" :disabled="processing">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">YY</label>
            <input v-model="card.year" type="text" class="w-full border rounded-lg p-3" 
              placeholder="25" maxlength="2" :disabled="processing">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">CVV</label>
            <input v-model="card.cvv" type="password" class="w-full border rounded-lg p-3" 
              maxlength="4" :disabled="processing">
          </div>
        </div>
      </div>

      <!-- Pay Button -->
      <button 
        @click="pay"
        :disabled="processing"
        class="w-full mt-6 bg-[#3D2817] hover:bg-[#2c1d12] text-white font-semibold py-3 rounded-lg disabled:opacity-50">
        {{ processing ? 'Processing...' : `Pay $${amount}` }}
      </button>

      <p class="text-center text-xs text-gray-500 mt-4">
        Secured by Paystack
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'

const props = defineProps({
  reference: String,
  email: String,
  amount: Number,
  membership: String,
})

const card = ref({
  number: '',
  month: '',
  year: '',
  cvv: ''
})

const processing = ref(false)
const errorMessage = ref('')

async function pay() {
  errorMessage.value = ''
  
  // Validate inputs
  if (!card.value.number || !card.value.month || !card.value.year || !card.value.cvv) {
    errorMessage.value = 'Please fill in all card details'
    return
  }

  processing.value = true

  try {
    const payload = {
      reference: props.reference,
      email: props.email,
      amount: props.amount * 100, // Convert to kobo
      card: card.value,
    }

    console.log('Sending payload:', payload)

    const response = await axios.post('/paystack/charge', payload)
    
    console.log('Response:', response.data)

    if (response.data.status === true && response.data.data.status === 'success') {
      router.visit(`/payment/callback?reference=${props.reference}`)
    }
    else if (response.data.data?.status === 'send_otp') {
      // Handle OTP case
      alert('OTP verification required - implement OTP modal')
    }
    else {
      errorMessage.value = response.data.message || 'Payment failed. Please try again.'
    }

  } catch (error) {
    console.error('Payment error:', error)
    errorMessage.value = error.response?.data?.message || 'An error occurred. Please try again.'
  } finally {
    processing.value = false
  }
}
</script>