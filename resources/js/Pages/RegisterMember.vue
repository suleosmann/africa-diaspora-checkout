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
          <!-- First Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">First Name</label>
            <div class="relative">
              <input
                v-model="form.first_name"
                type="text"
                placeholder="Enter your first name"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-user"></i>
              </span>
            </div>
            <p v-if="form.errors.first_name" class="text-red-600 text-sm mt-1">{{ form.errors.first_name }}</p>
          </div>

          <!-- Last Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Last Name</label>
            <div class="relative">
              <input
                v-model="form.last_name"
                type="text"
                placeholder="Enter your last name"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-user"></i>
              </span>
            </div>
            <p v-if="form.errors.last_name" class="text-red-600 text-sm mt-1">{{ form.errors.last_name }}</p>
          </div>
        </div>

        <!-- Phone Number with Country Code (Full Width) -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
          <div class="flex gap-2">
            <!-- Country Code Dropdown -->
            <div class="relative w-32">
              <button
                @click="showPhoneDropdown = !showPhoneDropdown"
                type="button"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-3 text-gray-900 focus:ring-2 focus:ring-[#FFDA9E] flex items-center justify-between"
              >
                <span class="flex items-center gap-1 text-sm">
                  <span>{{ selectedCountryCode.flag }}</span>
                  <span>{{ selectedCountryCode.dial_code }}</span>
                </span>
                <i class="fa fa-chevron-down text-xs"></i>
              </button>
              
              <!-- Phone Code Dropdown -->
              <div 
                v-if="showPhoneDropdown"
                class="absolute z-50 w-72 mt-1 bg-white rounded-lg shadow-lg max-h-60 overflow-y-auto border border-gray-200"
              >
                <div class="sticky top-0 bg-white z-10">
                  <input
                    v-model="phoneSearchQuery"
                    @click.stop
                    type="text"
                    placeholder="Search country..."
                    class="w-full border-b border-gray-200 px-3 py-2 text-sm focus:outline-none bg-white"
                  />
                </div>
                <div
                  v-for="country in filteredPhoneCodes"
                  :key="country.cca2"
                  @mousedown="selectPhoneCode(country)"
                  class="px-3 py-2 hover:bg-[#FFDA9E] cursor-pointer text-sm flex items-center gap-2"
                >
                  <span>{{ country.flag }}</span>
                  <span class="flex-1">{{ country.name }}</span>
                  <span class="text-gray-600">{{ country.dial_code }}</span>
                </div>
              </div>
            </div>

            <!-- Phone Number Input -->
            <div class="flex-1 relative">
              <input
                v-model="form.phone"
                type="tel"
                placeholder="712345678"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-[#FFDA9E]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-phone"></i>
              </span>
            </div>
          </div>
          <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
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

        <!-- Membership Type Selection -->
      <div>
        <label class="block text-sm font-semibold text-gray-900 mb-3">Select Membership Type</label>
        <div class="grid grid-cols-3 gap-3">
          <!-- Join Network Membership -->
          <div 
            @click="selectMembership(0)"
            :class="[
              'rounded-lg p-2 border-2 cursor-pointer transition text-center',
              form.register_type === 0 
                ? 'border-[#3D2817] bg-[#FFDA9E]' 
                : 'border-gray-300 bg-white hover:border-gray-400'
            ]"
          >
            <div class="flex justify-center mb-1">
              <input 
                type="radio" 
                :checked="form.register_type === 0"
                class="text-[#3D2817] focus:ring-[#FFDA9E]"
              />
            </div>
            <p class="font-bold text-[#3D2817] text-sm">Join Network</p>
            <p class="text-xs text-gray-700 mt-0.5">Basic access</p>
          </div>

          <!-- Become Member -->
          <div 
            @click="selectMembership(1)"
            :class="[
              'rounded-lg p-2 border-2 cursor-pointer transition text-center',
              form.register_type === 1 
                ? 'border-[#3D2817] bg-[#FFDA9E]' 
                : 'border-gray-300 bg-white hover:border-gray-400'
            ]"
          >
            <div class="flex justify-center mb-1">
              <input 
                type="radio" 
                :checked="form.register_type === 1"
                class="text-[#3D2817] focus:ring-[#FFDA9E]"
              />
            </div>
            <p class="font-bold text-[#3D2817] text-sm whitespace-nowrap">Become Member</p>
            <p class="text-xs text-gray-700 mt-0.5">Download access</p>
          </div>

          <!-- Premier Membership -->
          <div 
            @click="selectMembership(-1)"
            :class="[
              'rounded-lg p-2 border-2 cursor-pointer transition text-center',
              form.register_type === -1 
                ? 'border-[#3D2817] bg-[#FFDA9E]' 
                : 'border-gray-300 bg-white hover:border-gray-400'
            ]"
          >
            <div class="flex justify-center mb-1">
              <input 
                type="radio" 
                :checked="form.register_type === -1"
                class="text-[#3D2817] focus:ring-[#FFDA9E]"
              />
            </div>
            <p class="font-bold text-[#3D2817] text-sm">Premier</p>
            <p class="text-xs text-gray-700 mt-0.5">$350/year</p>
          </div>
        </div>
        <p v-if="form.errors.register_type" class="text-red-600 text-sm mt-1">{{ form.errors.register_type }}</p>
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
          :disabled="form.processing || form.register_type === null"
          class="w-full bg-[#3D2817] hover:bg-[#2a1d13] text-white font-semibold py-3 rounded-lg transition disabled:opacity-50"
        >
          {{ form.processing ? 'Processing...' : getMembershipButtonText() }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const PAYSTACK_PUBLIC_KEY = import.meta.env.VITE_PAYSTACK_PUBLIC_KEY

const countries = ref([])
const searchQuery = ref('')
const showDropdown = ref(false)

// Phone code dropdown
const showPhoneDropdown = ref(false)
const phoneSearchQuery = ref('')
const countriesWithDialCodes = ref([])
const selectedCountryCode = ref({
  name: 'United States',
  dial_code: '+1',
  cca2: 'US',
  flag: '🇺🇸'
})
const handleClickOutside = (event) => {
  const phoneDropdown = event.target.closest('.relative.w-32')
  if (!phoneDropdown && showPhoneDropdown.value) {
    showPhoneDropdown.value = false
    phoneSearchQuery.value = ''
  }
}
// Helper function to get flag emoji from country code
const getFlagEmoji = (countryCode) => {
  if (!countryCode) return ''
  const codePoints = countryCode
    .toUpperCase()
    .split('')
    .map(char => 127397 + char.charCodeAt())
  return String.fromCodePoint(...codePoints)
}

onMounted(async () => {
  const worldCountries = await import('world-countries')
  
  // For the region dropdown
  countries.value = worldCountries.default
    .map(country => country.name.common)
    .sort()
  
  // For phone codes - extract dial codes and create proper structure
  countriesWithDialCodes.value = worldCountries.default
    .map(country => {
      // Get the full dial code
      const root = country.idd.root || ''
      const suffixes = country.idd.suffixes || []
      
      // For countries like US with multiple suffixes, just use the root
      const dial_code = suffixes.length === 0 || suffixes.length > 1 
        ? root 
        : root + suffixes[0]
      
      return {
        name: country.name.common,
        cca2: country.cca2,
        dial_code: dial_code,
        flag: getFlagEmoji(country.cca2)
      }
    })
    .filter(country => country.dial_code && country.dial_code !== '+') // Filter out countries without dial codes
    .sort((a, b) => a.name.localeCompare(b.name))
  
  // Set United States as default
  const usa = countriesWithDialCodes.value.find(c => c.cca2 === 'US')
  if (usa) {
    selectedCountryCode.value = usa
    form.country_code = usa.dial_code
  }
    document.addEventListener('click', handleClickOutside)

})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

const filteredPhoneCodes = computed(() => {
  if (!phoneSearchQuery.value) return countriesWithDialCodes.value
  return countriesWithDialCodes.value.filter(country => 
    country.name.toLowerCase().includes(phoneSearchQuery.value.toLowerCase()) ||
    country.dial_code.includes(phoneSearchQuery.value)
  )
})

const selectPhoneCode = (country) => {
  selectedCountryCode.value = country
  form.country_code = country.dial_code
  showPhoneDropdown.value = false
  phoneSearchQuery.value = ''
}

// const hidePhoneDropdown = () => {
//   setTimeout(() => {
//     showPhoneDropdown.value = false
//   }, 200)
// }

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
  first_name: '',
  last_name: '',
  phone: '',
  country_code: '+1',
  email: '',
  industry: '',
  region: '',
  register_type: null,
  agree: false,
})

const selectMembership = (type) => {
  form.register_type = type
}

const getMembershipButtonText = () => {
  if (form.register_type === null) {
    return 'Select Membership Type'
  }
  if (form.register_type === 0) {
    return 'Join Network'
  }
  if (form.register_type === 1) {
    return 'Become Member'
  }
  return 'Proceed to Payment'
}

async function submit() {
  // Combine first and last name for backend
  const submitData = {
    ...form.data(),
    name: `${form.first_name} ${form.last_name}`.trim()
  }
  
  console.log('🟡 Submitting form:', submitData)

  if (form.register_type === null) {
    alert('⚠️ Please select a membership type')
    return
  }

  form.processing = true

  try {
    const response = await axios.post('/register-member', submitData)
    const data = response.data

    console.log('✅ Member registered:', data)

    // Join Network (Type 0) - redirect to join network success page
    if (form.register_type === 0) {
      window.location.href = '/join-network-success'
      return
    }

    // Become Member (Type 1) - redirect to download success page
    if (form.register_type === 1) {
      window.location.href = '/download-success'
      return
    }

    // Premier membership (-1) - continue with payment
    if (!PAYSTACK_PUBLIC_KEY) {
      alert('⚠️ Paystack key not configured. Please contact support.')
      form.processing = false
      return
    }

    if (!window.PaystackPop) {
      alert('⚠️ Paystack script not loaded. Please refresh the page.')
      form.processing = false
      return
    }

    const handler = window.PaystackPop.setup({
      key: PAYSTACK_PUBLIC_KEY,
      email: data.email,
      amount: data.amount * 100,
      currency: 'USD',
      ref: data.reference,
      metadata: {
        custom_fields: [
          {
            display_name: 'Member Name',
            variable_name: 'member_name',
            value: submitData.name
          },
          {
            display_name: 'Membership Type',
            variable_name: 'membership_type',
            value: data.membership_name
          }
        ]
      },
      callback: function(response) {
        console.log('✅ Payment successful:', response)
        window.location.href = `/payment/callback?reference=${response.reference}`
      },
      onClose: function() {
        console.log('⚠️ Payment popup closed')
        form.processing = false
      }
    })

    handler.openIframe()

  } catch (error) {
    console.error('❌ Registration error:', error)
    alert('Something went wrong. Please try again.')
    form.processing = false
  }
}
</script>