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
      <div class="space-y-4" v-show="!showOtpInput && !show3dsFrame">
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-1">Card Number</label>
          <input v-model="card.number" type="text"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FFDA9E]" 
            placeholder="1234 5678 9012 3456"
            maxlength="19"
            @input="formatCardNumber"
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

        <!-- Pay Button -->
        <button 
          @click="pay"
          :disabled="processing"
          class="w-full mt-6 bg-[#3D2817] hover:bg-[#2c1d12] text-white font-semibold py-3 rounded-lg disabled:opacity-50 transition">
          {{ processing ? 'Processing...' : `Pay $${amount}` }}
        </button>
      </div>

      <!-- OTP Input Section -->
      <div v-if="showOtpInput" class="space-y-4">
        <p class="text-sm text-gray-700 mb-4">
          An OTP has been sent to your phone. Please enter it below.
        </p>
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-1">Enter OTP</label>
          <input v-model="otp" type="text"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-[#FFDA9E]" 
            placeholder="123456"
            maxlength="6"
            @keyup.enter="submitOtp">
        </div>
        <button 
          @click="submitOtp"
          :disabled="processing || !otp"
          class="w-full bg-[#3D2817] hover:bg-[#2c1d12] text-white font-semibold py-3 rounded-lg disabled:opacity-50 transition">
          {{ processing ? 'Verifying...' : 'Submit OTP' }}
        </button>
      </div>

      <!-- 3D Secure Frame -->
      <div v-if="show3dsFrame" class="space-y-4">
        <iframe 
          :src="authUrl" 
          class="w-full h-96 border rounded-lg"
        ></iframe>
        <p class="text-sm text-gray-600 text-center">
          <i class="fa fa-lock mr-1"></i>
          Complete the authentication in the window above
        </p>
        <div class="flex justify-center">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#3D2817]"></div>
        </div>
      </div>

      <p class="text-center text-xs text-gray-500 mt-4">
        🔒 Secured by Paystack
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue'
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
const showOtpInput = ref(false)
const show3dsFrame = ref(false)
const otp = ref('')
const authUrl = ref('')
let checkInterval = null

function formatCardNumber(event) {
  let value = event.target.value.replace(/\s/g, '')
  let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value
  card.value.number = formattedValue
}

async function pay() {
  errorMessage.value = ''
  
  // Validate inputs
  if (!card.value.number || !card.value.month || !card.value.year || !card.value.cvv) {
    errorMessage.value = 'Please fill in all card details'
    return
  }

  // Validate card number length (remove spaces)
  const cleanNumber = card.value.number.replace(/\s/g, '')
  if (cleanNumber.length < 13 || cleanNumber.length > 19) {
    errorMessage.value = 'Invalid card number'
    return
  }

  // Validate month
  const month = parseInt(card.value.month)
  if (month < 1 || month > 12) {
    errorMessage.value = 'Invalid month'
    return
  }

  // Validate year
  const year = parseInt(card.value.year)
  if (year < 25 || year > 50) {
    errorMessage.value = 'Invalid year'
    return
  }

  processing.value = true

  try {
    const payload = {
      reference: props.reference,
      email: props.email,
      amount: props.amount * 100, // Convert to cents
      card: {
        number: card.value.number.replace(/\s/g, ''),
        month: card.value.month.padStart(2, '0'),
        year: card.value.year,
        cvv: card.value.cvv
      },
    }

    console.log('Submitting payment...')
    const response = await axios.post('/paystack/charge', payload)
    
    console.log('Charge response:', response.data)
    handleChargeResponse(response.data)

  } catch (error) {
    console.error('Payment error:', error)
    errorMessage.value = error.response?.data?.message || 'An error occurred. Please try again.'
    processing.value = false
  }
}

function handleChargeResponse(data) {
  if (!data.status) {
    errorMessage.value = data.message || 'Payment failed. Please try again.'
    processing.value = false
    return
  }

  const status = data.data?.status

  console.log('Payment status:', status)

  if (status === 'success') {
    // Payment successful immediately
    console.log('Payment successful!')
    router.visit(`/payment/callback?reference=${data.data.reference}`)
  } 
  else if (status === 'send_otp') {
    // OTP required
    console.log('OTP required')
    showOtpInput.value = true
    processing.value = false
  }
  else if (status === 'send_pin') {
    // PIN required
    errorMessage.value = 'Card requires PIN. Please use a different card.'
    processing.value = false
  }
  else if (status === 'pending' && data.data.url) {
    // 3D Secure authentication required
    console.log('3DS required:', data.data.url)
    authUrl.value = data.data.url
    show3dsFrame.value = true
    processing.value = false
    startPolling(data.data.reference)
  }
  else {
    errorMessage.value = data.message || 'Payment could not be processed. Please try again.'
    processing.value = false
  }
}

async function submitOtp() {
  if (!otp.value || otp.value.length < 4) {
    errorMessage.value = 'Please enter a valid OTP'
    return
  }

  processing.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post('/paystack/submit-otp', {
      otp: otp.value,
      reference: props.reference
    })

    console.log('OTP response:', response.data)
    handleChargeResponse(response.data)

  } catch (error) {
    console.error('OTP error:', error)
    errorMessage.value = error.response?.data?.message || 'Invalid OTP. Please try again.'
    otp.value = ''
    processing.value = false
  }
}

function startPolling(paystackReference) {
  console.log('Starting status polling for:', paystackReference)
  
  // Poll every 3 seconds
  checkInterval = setInterval(async () => {
    try {
      const response = await axios.get(`/paystack/check-status/${paystackReference}`)
      
      console.log('Status check:', response.data)

      if (response.data.status === 'success') {
        clearInterval(checkInterval)
        console.log('Payment verified! Redirecting...')
        
        // Use the original reference for redirect
        const redirectRef = response.data.original_reference || props.reference
        router.visit(`/payment/callback?reference=${redirectRef}`)
      } 
      else if (response.data.status === 'failed') {
        clearInterval(checkInterval)
        errorMessage.value = 'Payment failed. Please try again.'
        show3dsFrame.value = false
        processing.value = false
      }
    } catch (error) {
      console.error('Status check error:', error)
    }
  }, 3000)
}

onBeforeUnmount(() => {
  if (checkInterval) {
    clearInterval(checkInterval)
  }
})
</script>