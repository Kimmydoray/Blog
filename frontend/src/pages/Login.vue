<template>
  <q-page class="flex flex-center">
    <q-card class="login-card">
      <q-card-section class="text-center">
        <h4 class="q-mt-none q-mb-md">Blog Management System</h4>
      </q-card-section>
      
      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            filled
            v-model="email"
            label="Email"
            type="email"
            :rules="[val => !!val || 'Email is required', isValidEmail]"
          />
          
          <q-input
            filled
            v-model="password"
            label="Password"
            :type="isPwd ? 'password' : 'text'"
            :rules="[val => !!val || 'Password is required']"
          >
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>
          
          <div class="full-width q-pt-md">
            <q-btn
              label="Login"
              type="submit"
              color="primary"
              class="full-width"
              :loading="loading"
            />
          </div>
        </q-form>
      </q-card-section>
      
      <q-card-section class="text-center q-pt-none">
        <q-banner v-if="error" class="bg-red-1 text-red">
          {{ error }}
        </q-banner>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useRouter } from 'vue-router'
// import { useQuasar } from 'quasar'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'LoginPage',
  
  setup() {
    // Make sure Quasar is properly initialized
    const router = useRouter()
    
    const email = ref('')
    const password = ref('')
    const isPwd = ref(true)
    const loading = ref(false)
    const error = ref('')
    
    const isValidEmail = (val) => {
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
      return emailPattern.test(val) || 'Invalid email format'
    }
    
    const onSubmit = async () => {
      loading.value = true
      error.value = ''
      
      try {
        console.log('Attempting login...')
        const response = await api.post('/api/login', {
          email: email.value,
          password: password.value
        })
        
        console.log('Login response:', response.data)
        
        // Check if we have the expected response format
        if (response.data && response.data.access_token) {
          // Store token in localStorage
          localStorage.setItem('access_token', response.data.access_token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          
          // Set default authorization header for all future requests
          api.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`
          
          // Skip notification for now
          console.log('Login successful')
          
          // Add short delay before redirect (to ensure token is stored)
          setTimeout(() => {
            // Redirect to blog management page
            router.push('/blogs')
          }, 300)
        } else {
          console.error('Invalid response format', response.data)
          error.value = 'Login failed: Invalid response from server'
        }
      } catch (err) {
        console.error('Login error:', err)
        // More detailed error logging
        if (err.response) {
          console.error('Error response:', err.response.data)
          console.error('Status code:', err.response.status)
        }
        error.value = err.response?.data?.message || 'Login failed. Please try again.'
      } finally {
        loading.value = false
      }
    }
    
    return {
      email,
      password,
      isPwd,
      loading,
      error,
      isValidEmail,
      onSubmit
    }
  }
})
</script>

<style scoped>
.login-card {
  width: 100%;
  max-width: 400px;
}
</style>