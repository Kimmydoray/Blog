<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>
          Blog Management System
        </q-toolbar-title>

        <div>{{ user.name }}</div>
        <q-btn
          flat
          round
          dense
          icon="logout"
          @click="logout"
          class="q-ml-sm"
        />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { api } from 'src/boot/axios'

export default defineComponent({
  name: 'MainLayout',

  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const user = ref({
      name: '',
      email: ''
    })

    onMounted(() => {
      const storedUser = localStorage.getItem('user')
      if (storedUser) {
        user.value = JSON.parse(storedUser)
      }
    })

    const logout = async () => {
      try {
        await api.post('/api/logout')
        localStorage.removeItem('access_token')
        localStorage.removeItem('user')
        router.push('/login')
        
        $q.notify({
          color: 'positive',
          message: 'Logged out successfully',
          icon: 'check'
        })
      } catch (error) {
        console.error('Logout error:', error)
        
        // Even if API call fails, clear local storage and redirect
        localStorage.removeItem('access_token')
        localStorage.removeItem('user')
        router.push('/login')
      }
    }

    return {
      user,
      logout
    }
  }
})
</script>