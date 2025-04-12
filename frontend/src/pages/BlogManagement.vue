<template>
  <q-page padding>
    <div class="row q-mb-md">
      <div class="col-6">
        <h4 class="q-my-none">Blog Management</h4>
      </div>
      <div class="col-6 flex justify-end items-center">
        <q-input
          v-model="search"
          dense
          outlined
          placeholder="Search by title"
          class="q-mr-md"
          @update:model-value="onSearch"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
        <q-btn
          color="primary"
          label="Add Blog"
          @click="showAddBlogModal = true"
        />
      </div>
    </div>
    
    <div v-if="loading" class="text-center q-pa-md">
      <q-spinner color="primary" size="3em" />
      <div class="q-mt-md">Loading blogs...</div>
    </div>
    
    <div v-else-if="blogs.length === 0" class="text-center q-pa-xl">
      <p>No blogs found. Create your first blog by clicking "Add Blog" above.</p>
    </div>
    
    <q-table
      v-else
      :rows="blogs"
      :columns="columns"
      row-key="id"
      :filter="search"
      no-data-label="No blogs found"
      no-results-label="No blogs match your search terms"
    >
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="title" :props="props">{{ props.row.title }}</q-td>
          <q-td key="status" :props="props">
            <q-chip
              :color="props.row.status === 'published' ? 'green' : 'grey'"
              text-color="white"
              size="sm"
            >
              {{ props.row.status }}
            </q-chip>
          </q-td>
          <q-td key="created_at" :props="props">
            {{ formatDate(props.row.created_at) }}
          </q-td>
          <q-td key="actions" :props="props">
            <q-btn-dropdown flat color="primary" label="Actions">
              <q-list>
                <q-item clickable v-close-popup @click="editBlog(props.row)">
                  <q-item-section>
                    <q-item-label>Edit</q-item-label>
                  </q-item-section>
                </q-item>
                
                <q-item clickable v-close-popup @click="toggleStatus(props.row)">
                  <q-item-section>
                    <q-item-label>
                      {{ props.row.status === 'published' ? 'Hide' : 'Publish' }}
                    </q-item-label>
                  </q-item-section>
                </q-item>
                
                <q-item clickable v-close-popup @click="previewBlog(props.row)">
                  <q-item-section>
                    <q-item-label>Preview</q-item-label>
                  </q-item-section>
                </q-item>
                
                <q-item clickable v-close-popup @click="archiveBlog(props.row)">
                  <q-item-section>
                    <q-item-label>Archive</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </q-td>
        </q-tr>
      </template>
    </q-table>
    
    <!-- Add Blog Modal -->
    <add-blog-modal
      v-model="showAddBlogModal"
      @blog-added="fetchBlogs"
    />
    
    <!-- Edit Blog Modal -->
    <edit-blog-modal
      v-model="showEditBlogModal"
      :blog="selectedBlog"
      @blog-updated="fetchBlogs"
    />
    
    <!-- Preview Blog Modal -->
    <preview-blog-modal
      v-model="showPreviewModal"
      :blog="selectedBlog"
    />
    
    <!-- Confirmation Dialog -->
    <q-dialog v-model="confirmDialog.show" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="warning" color="warning" text-color="white" />
          <span class="q-ml-sm">{{ confirmDialog.message }}</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" v-close-popup />
          <q-btn flat label="Confirm" color="negative" @click="confirmDialog.onConfirm" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { date, useQuasar } from 'quasar'
import { api } from 'src/boot/axios'
import AddBlogModal from 'components/AddBlogModal.vue'
import EditBlogModal from 'components/EditBlogModal.vue'
import PreviewBlogModal from 'components/PreviewBlogModal.vue'

export default defineComponent({
  name: 'BlogManagement',
  
  components: {
    AddBlogModal,
    EditBlogModal,
    PreviewBlogModal
  },
  
  setup() {
    const $q = useQuasar()
    const blogs = ref([])
    const loading = ref(false)
    const search = ref('')
    const showAddBlogModal = ref(false)
    const showEditBlogModal = ref(false)
    const showPreviewModal = ref(false)
    const selectedBlog = ref({})
    
    const confirmDialog = ref({
      show: false,
      message: '',
      onConfirm: () => {}
    })
    
    const columns = [
      {
        name: 'title',
        required: true,
        label: 'Title',
        align: 'left',
        field: row => row.title,
        sortable: true
      },
      {
        name: 'status',
        required: true,
        label: 'Status',
        align: 'center',
        field: row => row.status,
        sortable: true
      },
      {
        name: 'created_at',
        required: true,
        label: 'Created Date',
        align: 'left',
        field: row => row.created_at,
        sortable: true
      },
      {
        name: 'actions',
        required: true,
        label: 'Actions',
        align: 'center',
        field: 'actions'
      }
    ]
    
    const formatDate = (dateStr) => {
      return date.formatDate(dateStr, 'MMMM D, YYYY')
    }
    
    const fetchBlogs = async () => {
      loading.value = true
      try {
        console.log('Fetching blogs...')
        const response = await api.get('/api/blogs', {
          params: { search: search.value }
        })
        console.log('API response:', response)
        blogs.value = response.data
        console.log('Blogs loaded:', blogs.value)
      } catch (error) {
        console.error('Error fetching blogs:', error)
        if (error.response) {
          console.error('Error response:', error.response.data)
          console.error('Status code:', error.response.status)
        }
        // No need for notification here as it might cause issues
      } finally {
        loading.value = false
      }
    }
    
    const onSearch = () => {
      fetchBlogs()
    }
    
    const editBlog = (blog) => {
      selectedBlog.value = { ...blog }
      showEditBlogModal.value = true
    }
    
    const previewBlog = (blog) => {
      selectedBlog.value = { ...blog }
      showPreviewModal.value = true
    }
    
    const toggleStatus = async (blog) => {
      try {
        const newStatus = blog.status === 'published' ? 'hidden' : 'published'
        await api.patch(`/api/blogs/${blog.id}/status`, {
          status: newStatus
        })
        
        $q.notify({
          color: 'positive',
          message: `Blog status updated to ${newStatus}`,
          icon: 'check'
        })
        
        fetchBlogs()
      } catch (error) {
        console.error('Error updating blog status:', error)
        $q.notify({
          color: 'negative',
          message: 'Failed to update blog status',
          icon: 'error'
        })
      }
    }
    
    const archiveBlog = (blog) => {
      confirmDialog.value = {
        show: true,
        message: `Are you sure you want to archive "${blog.title}"?`,
        onConfirm: async () => {
          try {
            await api.delete(`/api/blogs/${blog.id}`)
            
            $q.notify({
              color: 'positive',
              message: 'Blog archived successfully',
              icon: 'check'
            })
            
            fetchBlogs()
          } catch (error) {
            console.error('Error archiving blog:', error)
            $q.notify({
              color: 'negative',
              message: 'Failed to archive blog',
              icon: 'error'
            })
          }
        }
      }
    }
    
    onMounted(() => {
      fetchBlogs()
    })
    
    return {
      blogs,
      columns,
      loading,
      search,
      showAddBlogModal,
      showEditBlogModal,
      showPreviewModal,
      selectedBlog,
      confirmDialog,
      formatDate,
      fetchBlogs,
      onSearch,
      editBlog,
      previewBlog,
      toggleStatus,
      archiveBlog
    }
  }
})
</script>