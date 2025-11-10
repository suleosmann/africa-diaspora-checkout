<template>
    <div>
        <Navbar />
    </div>
    
  <div class="min-h-screen flex items-center justify-center  p-4">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-semibold text-center text-gray-800 mb-6">
        Make a Contribution
      </h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-700 p-3 rounded-md text-center">
          {{ $page.props.flash.success }}
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition"
            :class="{ 'border-red-500': form.errors.name }"
            required
          />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
            {{ form.errors.name }}
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition"
            :class="{ 'border-red-500': form.errors.email }"
            required
          />
          <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
            {{ form.errors.email }}
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Amount (KES)</label>
          <input
            v-model="form.amount"
            type="number"
            min="1"
            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 transition"
            :class="{ 'border-red-500': form.errors.amount }"
            required
          />
          <div v-if="form.errors.amount" class="text-red-600 text-sm mt-1">
            {{ form.errors.amount }}
          </div>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition disabled:opacity-50"
        >
          {{ form.processing ? 'Processing...' : 'Contribute Now' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  amount: '',
})

function submit() {
  form.post('/contribute', {
    preserveScroll: true,
    onSuccess: () => {
    //   form.reset()
    },
    onError: (errors) => {
      console.log('Validation errors:', errors)
    }
  })
}

</script>