<template>
    <div>
        <Navbar />
    </div>
  <div class="flex items-center justify-center p-6 mt-8">
    <div class="w-full max-w-md">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Create your account
      </h1>
      <p class="text-gray-700 mb-8">
        Create your Aden Africa account to manage and pay
      </p>

      <!-- Registration Form -->
      <form @submit.prevent="submit" class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
          <!-- Full Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
            <div class="relative">
              <input
                v-model="form.name"
                type="text"
                placeholder="Enter your full name"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-user"></i>
              </span>
            </div>
            <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <!-- Phone Number -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
            <div class="relative">
              <input
                v-model="form.phone"
                type="text"
                placeholder="Enter phone number"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-phone"></i>
              </span>
            </div>
            <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <!-- Industry Affiliation -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Industry Affiliation</label>
            <div class="relative">
              <input
                v-model="form.industry"
                type="text"
                placeholder="Industry Affiliation"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-briefcase"></i>
              </span>
            </div>
          </div>

          <!-- Country Searchable Dropdown -->
          <div class="relative">
            <label class="block text-sm font-semibold text-gray-900 mb-2">Country</label>
            <div class="relative">
              <input
                v-model="searchQuery"
                @focus="showDropdown = true"
                @blur="hideDropdown"
                type="text"
                placeholder="Search country..."
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900 pointer-events-none">
                <i class="fa fa-chevron-down"></i>
              </span>
              
              <!-- Dropdown -->
              <div 
                v-if="showDropdown && filteredCountries.length > 0"
                class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg max-h-60 overflow-y-auto border border-gray-200"
              >
                <div
                  v-for="country in filteredCountries"
                  :key="country"
                  @mousedown="selectCountry(country)"
                  class="px-4 py-2 hover:bg-[#FFDA9E] cursor-pointer text-gray-900"
                >
                  {{ country }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Email Address -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
          <div class="relative">
            <input
              v-model="form.email"
              type="email"
              placeholder="Enter your email address"
              class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
              required
            />
            <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
              <i class="fa fa-envelope"></i>
            </span>
          </div>
          <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Membership Type Info -->
        <div class="bg-[#FFDA9E] rounded-lg p-4 border-2 border-[#3D2817]">
          <div class="flex justify-between items-center">
            <div>
              <p class="font-bold text-[#3D2817] text-lg">Premier Membership</p>
              <p class="text-sm text-gray-700">Annual subscription</p>
            </div>
            <p class="font-bold text-[#3D2817] text-2xl">$350</p>
          </div>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3">
          <input 
            id="agree" 
            v-model="form.agree" 
            type="checkbox" 
            required 
            class="mt-1 rounded border-gray-300 text-[#3D2817] focus:ring-[#FFDA9E]" 
          />
          <label for="agree" class="text-sm text-gray-900">
            I agree to the
            <a href="#" class="text-[#3D2817] hover:underline font-semibold">Terms & Conditions</a> and
            <a href="#" class="text-[#3D2817] hover:underline font-semibold">Privacy Policy</a>
          </label>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-[#3D2817] hover:bg-[#2a1d13] text-white font-semibold py-3 rounded-lg transition disabled:opacity-50"
        >
          {{ form.processing ? 'Processing...' : 'Proceed to Payment' }}
        </button>

        <!-- <p class="text-center text-sm text-gray-900 mt-4">
          Already have an account?
          <a href="/login" class="text-[#3D2817] hover:underline font-semibold">Sign in here</a>
        </p> -->
      </form>
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, computed, onMounted } from 'vue'

const PAYSTACK_PUBLIC_KEY = import.meta.env.VITE_PAYSTACK_PUBLIC_KEY

const countries = ref([])
const searchQuery = ref('')
const showDropdown = ref(false)

onMounted(async () => {
  const worldCountries = await import('world-countries')
  countries.value = worldCountries.default
    .map(country => country.name.common)
    .sort()
})

const filteredCountries = computed(() => {
  if (!searchQuery.value) return countries.value
  return countries.value.filter(country => 
    country.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const selectCountry = (country) => {
  form.region = country
  searchQuery.value = country
  showDropdown.value = false
}

const hideDropdown = () => {
  setTimeout(() => {
    showDropdown.value = false
  }, 200)
}

const form = useForm({
  name: '',
  phone: '',
  email: '',
  industry: '',
  region: '',
  agree: false,
})

async function submit() {
  console.log('🟡 Submitting form:', form.data())

  if (!PAYSTACK_PUBLIC_KEY) {
    alert('⚠️ Paystack key not configured. Please contact support.')
    return
  }

  if (!window.PaystackPop) {
    alert('⚠️ Paystack script not loaded. Please refresh the page.')
    return
  }

  form.processing = true

  try {
    const response = await axios.post('/register-member', form.data())
    const data = response.data
    console.log('✅ Backend response:', data)

    const handler = window.PaystackPop.setup({
      key: PAYSTACK_PUBLIC_KEY,
      email: data.email,
      amount: parseFloat(data.amount) * 100,
      currency: 'USD',
      ref: data.reference,
      label: 'Aden Africa Membership',
      metadata: {
        name: form.name,
        phone: form.phone,
        membership: data.membership_name,
      },
      callback: (response) => {
        console.log('✅ Payment success:', response)
        window.location.href = `/payment/callback?reference=${response.reference}`
      },
      onClose: () => {
        console.log('❌ Payment window closed.')
        form.processing = false
      },
    })

    handler.openIframe()
  } catch (error) {
    console.error('❌ Error:', error.response?.data || error)
    alert('Something went wrong. Please try again.')
  } finally {
    form.processing = false
  }
}
</script>