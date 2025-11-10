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
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-yellow-400"
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
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-yellow-400"
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
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-yellow-400"
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-briefcase"></i>
              </span>
            </div>
          </div>

          <!-- Region -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Region</label>
            <div class="relative">
              <input
                v-model="form.region"
                type="text"
                placeholder="Region"
                class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-yellow-400"
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
                <i class="fa fa-map-marker-alt"></i>
              </span>
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
              class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 pr-10 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-yellow-400"
              required
            />
            <span class="absolute inset-y-0 right-3 flex items-center text-gray-900">
              <i class="fa fa-envelope"></i>
            </span>
          </div>
          <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Membership Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">Membership Type</label>
          <select
            v-model="form.membership_type_id"
            class="w-full rounded-lg border-0 bg-white shadow-sm py-3 px-4 text-gray-900 focus:ring-2 focus:ring-yellow-400"
            required
          >
            <option value="">Select membership type</option>
            <option v-for="type in membershipTypes" :key="type.id" :value="type.id">
              {{ type.name }} - USD {{ type.amount }}
            </option>
          </select>
          <p v-if="form.errors.membership_type_id" class="text-red-600 text-sm mt-1">
            {{ form.errors.membership_type_id }}
          </p>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3">
          <input 
            id="agree" 
            v-model="form.agree" 
            type="checkbox" 
            required 
            class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
          />
          <label for="agree" class="text-sm text-gray-900">
            I agree to the
            <a href="#" class="text-blue-600 hover:underline">Terms & Conditions</a> and
            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
          </label>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-[#3D2817] hover:bg-[#2a1d13] text-white font-semibold py-3 rounded-lg transition disabled:opacity-50"
        >
          {{ form.processing ? 'Processing...' : 'Proceed' }}
        </button>

        <p class="text-center text-sm text-gray-900 mt-4">
          Already have an account?
          <a href="/login" class="text-blue-600 hover:underline">Sign in here</a>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'

const membershipTypes = ref([])

const form = useForm({
  name: '',
  phone: '',
  email: '',
  industry: '',
  region: '',
  membership_type_id: '',
  agree: false,
})

onMounted(async () => {
  try {
    const response = await axios.get('/membership-types')
    membershipTypes.value = response.data.data
  } catch (error) {
    console.error('Failed to load membership types:', error)
  }
})


async function submit() {
  console.log('Submitting form:', form.data())

  try {
    // use axios manually instead of Inertia's form.post
    const response = await axios.post('/register-member', form.data())

    if (response.data?.redirect_url) {
      console.log('🌍 Redirecting to:', response.data.redirect_url)
      window.location.href = response.data.redirect_url
    } else {
      console.warn('No redirect URL found in response', response.data)
    }
  } catch (error) {
    console.error('❌ Registration failed:', error.response?.data || error.message)
    if (error.response?.data?.error) {
      alert(error.response.data.error)
    }
  }
}
</script>