<template>
    <Navbar/>
  <div class="min-h-screen  flex justify-center items-center -mt-16 p-6">

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

      <!-- Card Form -->
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-1">Card Number</label>
          <input v-model="card.number" type="text"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FFDA9E]" 
            placeholder="1234 5678 9012 3456">
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">MM</label>
            <input v-model="card.month" type="text" class="w-full border rounded-lg p-3">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">YY</label>
            <input v-model="card.year" type="text" class="w-full border rounded-lg p-3">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">CVV</label>
            <input v-model="card.cvv" type="password" class="w-full border rounded-lg p-3">
          </div>
        </div>
      </div>

      <!-- Pay Button -->
      <button 
        @click="pay"
        class="w-full mt-6 bg-[#3D2817] hover:bg-[#2c1d12] text-white font-semibold py-3 rounded-lg">
        Pay ${{ amount }}
      </button>

      <p class="text-center text-xs text-gray-500 mt-4">
        Secured by Aden Africa
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

async function pay() {
  const payload = {
    reference: props.reference,
    email: props.email,
    amount: props.amount * 100,
    card: card.value,
  }

  const response = await axios.post('/paystack/charge', payload)

  if (response.data.status === 'success') {
    router.visit(`/payment/success/${props.reference}`)
  }

  if (response.data.status === 'send_otp') {
    // ✳️ Open your OTP modal here
  }

  if (response.data.status === 'failed') {
    router.visit(`/payment/failed/${props.reference}`)
  }
}
</script>
