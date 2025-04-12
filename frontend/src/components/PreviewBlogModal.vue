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
          <div class="text-h6">Blog Preview</div>
          <div class="text-caption q-ml-md">
            <q-chip
              :color="blog.status === 'published' ? 'green' : 'grey'"
              text-color="white"
              size="sm"
            >
              {{ blog.status }}
            </q-chip>
          </div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
  
        <q-card-section>
          <div class="text-h4 q-mb-md">{{ blog.title }}</div>
          
          <div class="text-caption q-mb-lg">
            <span v-if="blog.author">Author: {{ blog.author.name }}</span>
            <span class="q-ml-md">Created: {{ formatDate(blog.created_at) }}</span>
          </div>
          
          <div class="blog-content" v-html="blog.content"></div>
        </q-card-section>
        
        <q-card-actions align="right">
          <q-btn
            label="Close"
            color="primary"
            v-close-popup
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
</template>
  
<script>
  import { defineComponent, computed } from 'vue'
  import { date } from 'quasar'
  
  export default defineComponent({
    name: 'PreviewBlogModal',
    
    props: {
      modelValue: {
        type: Boolean,
        required: true
      },
      blog: {
        type: Object,
        required: true
      }
    },
    
    emits: ['update:modelValue'],
    
    setup(props, { emit }) {
      const localModelValue = computed({
        get: () => props.modelValue,
        set: (value) => emit('update:modelValue', value)
      })
      
      const formatDate = (dateStr) => {
        if (!dateStr) return ''
        return date.formatDate(dateStr, 'MMMM D, YYYY')
      }
      
      return {
        localModelValue,
        formatDate
      }
    }
  })
</script>
  
<style scoped>
  .blog-content {
    line-height: 1.6;
  }
  
  .blog-content >>> p {
    margin-bottom: 1rem;
  }
  
  .blog-content >>> h1, 
  .blog-content >>> h2, 
  .blog-content >>> h3, 
  .blog-content >>> h4 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .blog-content >>> ul, 
  .blog-content >>> ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
  }
  
  .blog-content >>> img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
  }
</style>