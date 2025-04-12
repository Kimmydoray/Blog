<template>
    <q-dialog
      v-model="localModelValue"
      persistent
      maximized
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Add New Blog</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
  
        <q-card-section>
          <q-form @submit="onSubmit" class="q-gutter-md">
            <q-input
              v-model="form.title"
              filled
              label="Title *"
              :rules="[val => !!val || 'Title is required']"
            />
  
            <q-editor 
              v-model="form.content" 
              min-height="300px"
              :toolbar="[
                ['bold', 'italic', 'strike', 'underline', 'quote', 'unordered', 'ordered'],
                ['undo', 'redo'], ['fullscreen']
              ]"
              :rules="[val => !!val || 'Content is required']"
            />
  
            <div class="row items-center">
              <div class="col-12 col-sm-6">
                <q-select
                  v-model="form.status"
                  :options="statusOptions"
                  filled
                  label="Status *"
                  :rules="[val => !!val || 'Status is required']"
                />
              </div>
            </div>
  
            <div class="row justify-end q-mt-md">
              <q-btn
                label="Cancel"
                color="grey-7"
                v-close-popup
                class="q-mr-sm"
              />
              <q-btn
                label="Save"
                type="submit"
                color="primary"
                :loading="loading"
              />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </template>
  
  <script>
  import { defineComponent, ref, computed, watch } from 'vue'
  import { useQuasar } from 'quasar'
  import { api } from 'src/boot/axios'
  
  export default defineComponent({
    name: 'AddBlogModal',
    
    props: {
      modelValue: {
        type: Boolean,
        required: true
      }
    },
    
    emits: ['update:modelValue', 'blog-added'],
    
    setup(props, { emit }) {
      const $q = useQuasar()
      const loading = ref(false)
      
      const localModelValue = computed({
        get: () => props.modelValue,
        set: (value) => emit('update:modelValue', value)
      })
      
      const form = ref({
        title: '',
        content: '',
        status: 'hidden'
      })
      
      const statusOptions = [
        { label: 'Published', value: 'published' },
        { label: 'Hidden', value: 'hidden' }
      ]
      
      const resetForm = () => {
        form.value = {
          title: '',
          content: '',
          status: 'hidden'
        }
      }
      
      watch(localModelValue, (newVal) => {
        if (newVal) {
          resetForm()
        }
      })
      
      const onSubmit = async () => {
        loading.value = true
        
        try {
          await api.post('/api/blogs', form.value)
          
          $q.notify({
            color: 'positive',
            message: 'Blog created successfully',
            icon: 'check'
          })
          
          localModelValue.value = false
          emit('blog-added')
        } catch (error) {
          console.error('Error creating blog:', error)
          
          $q.notify({
            color: 'negative',
            message: 'Failed to create blog',
            icon: 'error'
          })
        } finally {
          loading.value = false
        }
      }
      
      return {
        localModelValue,
        form,
        loading,
        statusOptions,
        onSubmit
      }
    }
  })
  </script>