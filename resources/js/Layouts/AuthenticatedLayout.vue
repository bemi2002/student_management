<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

// Inertia page props
const page = usePage()
const authUser = computed(() => page.props.auth?.user ?? { name: 'Guest', email: '-' })
const userRoles = computed(() => page.props.auth?.roles ?? [])
const sidebarOpen = ref(true)

// Sidebar links with roles and icons - reorganized order
const roleLinks = [
  { name: 'Dashboard', route: 'dashboard', roles: ['Admin','Teacher','Student'], icon: '📊' },
  { name: 'Chats', route: 'chat.index', roles: ['Admin','Teacher','Student'], icon: '💬' },
 
  { name: 'Courses', route: 'courses.index', roles: ['Admin','Teacher'], icon: '📖' },
  { name: 'Teacher Chats', route: 'teacher.chat', roles: ['Teacher'], icon: '💬' },

  { name: 'Course Type', route: 'course-type.index', roles: ['Admin','Teacher'], icon: '📚' },
  { name: 'Enrollments', route: 'enrollmentss.index', roles: ['Admin'], icon: '📝' },
  
  { name: 'Users', route: 'users.index', roles: ['Admin'], icon: '👤' },
 
  { name: 'Students', route: 'students.index', roles: ['Admin'], icon: '🧑‍🎓' },
  { name: 'Settings', route: 'settings.index', roles: ['Admin'], icon: '⚙️'},
  
  { name: 'Roles', route: 'roles.index', roles: ['Admin'], icon: '👥' },
 
  { name: 'Company Addresses', route: 'company-addresses.index', roles: ['Admin'], icon: '🏢' },
  { name: 'Locations', route: 'locations.index', roles: ['Admin'], icon: '📍' },
]

// Filter links based on roles
const filteredLinks = computed(() =>
  roleLinks.filter(link =>
    link.roles.some(role => userRoles.value.includes(role))
  )
)
const settingsOpen = ref(true)


// Group links by category with new order
const groupedLinks = computed(() => {
  const groups = {
    'Quick Access': [],
    'Learning Management': [],
    'User Management': [],
    'System Administration': [],
    'Settings': []
  }
  
  filteredLinks.value.forEach(link => {
    if (['Dashboard', 'Chats'].includes(link.name)) {
      groups['Quick Access'].push(link)
    } else if ([ 'Enrollments',].includes(link.name)) {
      groups['Learning Management'].push(link)
    } else if ([ 'Teachers', 'Students'].includes(link.name)) {
      groups['User Management'].push(link)
    } else if (['Permissions', 'Admins'].includes(link.name)) {
      groups['System Administration'].push(link)
    } else if(['Settings'].includes(link.name)){
      groups['Settings'].push(link)
    }
  
    
  })
  
  return Object.entries(groups).filter(([_, links]) => links.length > 0)
})
</script>

<template>
  <div class="flex h-screen bg-sky-50">
    <!-- Sidebar -->
    <aside 
      :class="[
        'flex flex-col transition-all duration-300 ease-in-out shadow-xl',
        sidebarOpen ? 'w-72' : 'w-20'
      ]" 
      class="bg-gradient-to-b from-sky-500 to-blue-500"
    >
      <!-- Logo Area -->
      <div class="flex items-center justify-between h-20 px-4 border-b border-sky-400">
        <div class="flex items-center space-x-3" v-if="sidebarOpen">
          <div class="flex items-center justify-center">
            <div class="relative">
              <!-- Logo Container -->
              <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                <!-- Book Icon representing education -->
                <div class="relative">
                  <div class="w-6 h-8 bg-gradient-to-r from-sky-400 to-blue-500 rounded-sm transform rotate-2"></div>
                  <div class="absolute top-0 left-0 w-6 h-8 border-2 border-white rounded-sm transform -rotate-1"></div>
                  <div class="absolute top-1 left-1 w-4 h-1 bg-white rounded-full"></div>
                  <div class="absolute top-3 left-1 w-4 h-1 bg-white/80 rounded-full"></div>
                  <div class="absolute top-5 left-1 w-4 h-1 bg-white/60 rounded-full"></div>
                </div>
              </div>
              <!-- Small graduation cap -->
              <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full flex items-center justify-center">
                <div class="w-2 h-1 bg-white rounded-full"></div>
              </div>
            </div>
          </div>
          <div>
            <h1 class="text-white font-bold text-lg leading-tight">Student</h1>
            <h1 class="text-white font-bold text-lg leading-tight">Management</h1>
            <p class="text-sky-100 text-xs mt-1">System</p>
          </div>
        </div>
        <button 
          @click="sidebarOpen = !sidebarOpen"
          class="p-2 rounded-lg hover:bg-sky-400 transition-colors"
          v-if="sidebarOpen"
        >
          <span class="text-white text-lg">←</span>
        </button>
        <div v-else class="flex items-center justify-center">
          <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
            <div class="relative">
              <div class="w-5 h-6 bg-gradient-to-r from-sky-400 to-blue-500 rounded-sm transform rotate-2"></div>
              <div class="absolute top-0 left-0 w-5 h-6 border-2 border-white rounded-sm transform -rotate-1"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-6">
        <ul class="space-y-2 px-3">
          <template v-for="[groupName, links] in groupedLinks" :key="groupName">
            <!-- Group Header -->
            <li v-if="sidebarOpen" class="px-3 py-2">
              <p class="text-sky-200 text-xs font-semibold uppercase tracking-wider">{{ groupName }}</p>
            </li>
            <!-- Links -->
            <li v-for="link in links" :key="link.name">
              <Link
                :href="route(link.route)"
                :class="[
                  'flex items-center rounded-xl transition-all duration-200 font-medium group',
                  sidebarOpen ? 'px-4 py-3' : 'px-3 py-3 justify-center'
                ]"
                class="text-sky-100 hover:bg-sky-400 hover:text-white hover:shadow-lg"
              >
                <span class="text-lg" :class="sidebarOpen ? 'mr-3' : ''">{{ link.icon }}</span>
                <span v-if="sidebarOpen" class="flex-1">{{ link.name }}</span>
                <div v-if="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-sky-500 text-white text-sm rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50 shadow-lg">
                  {{ link.name }}
                </div>
              </Link>
            </li>
          </template>
        </ul>
      </nav>

      <!-- Profile Section -->
      <div class="border-t border-sky-400 p-4">
        <div class="flex items-center space-x-3" v-if="sidebarOpen">
          <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow">
            <span class="text-sky-600 font-bold text-sm">{{ authUser.name.charAt(0).toUpperCase() }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-white font-semibold text-sm truncate">{{ authUser.name }}</div>
            <div class="text-sky-100 text-xs truncate">{{ authUser.email }}</div>
            <div class="flex space-x-1 mt-1">
              <span 
                v-for="role in userRoles" 
                :key="role"
                class="px-2 py-0.5 bg-sky-400 text-white rounded-full text-xs"
              >
                {{ role }}
              </span>
            </div>
          </div>
        </div>
        <div v-else class="flex justify-center">
          <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow">
            <span class="text-sky-600 font-bold text-xs">{{ authUser.name.charAt(0).toUpperCase() }}</span>
          </div>
        </div>

        <!-- Profile Actions -->
        <div :class="sidebarOpen ? 'flex space-x-2 mt-3' : 'flex flex-col space-y-1 mt-3'">
          <Link
            :href="route('profile.edit')"
            :class="[
              'flex items-center rounded-lg transition-colors text-sm font-medium',
              sidebarOpen ? 'px-3 py-2 flex-1 justify-center' : 'px-2 py-2 justify-center'
            ]"
            class="bg-sky-400 text-white hover:bg-sky-300 hover:shadow-md"
          >
            <span class="text-sm">👤</span>
            <span v-if="sidebarOpen" class="ml-2">Profile</span>
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            :class="[
              'flex items-center rounded-lg transition-colors text-sm font-medium',
              sidebarOpen ? 'px-3 py-2 flex-1 justify-center' : 'px-2 py-2 justify-center'
            ]"
            class="bg-white/20 text-white hover:bg-white/30 hover:shadow-md"
          >
            <span class="text-sm">🚪</span>
            <span v-if="sidebarOpen" class="ml-2">Logout</span>
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-8">
      <div class="bg-white rounded-2xl shadow-sm border border-sky-100 p-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: #0ea5e9;
}

nav::-webkit-scrollbar-thumb {
  background: #7dd3fc;
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #bae6fd;
}

/* Smooth transitions */
* {
  transition-property: color, background-color, border-color, transform, opacity;
  transition-duration: 200ms;
  transition-timing-function: ease-in-out;
}

/* Professional input styling */
:deep(select),
:deep(input),
:deep(textarea) {
  background-color: white;
  color: #1e293b;
  border: 1px solid #cbd5e1;
  border-radius: 0.75rem;
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  transition: all 0.2s ease-in-out;
}

:deep(select:focus),
:deep(input:focus),
:deep(textarea:focus) {
  outline: none;
  border-color: #0ea5e9;
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

:deep(button) {
  transition: all 0.2s ease-in-out;
}

:deep(.btn-primary) {
  background: linear-gradient(135deg, #0ea5e9, #0369a1);
  color: white;
  border: none;
  border-radius: 0.75rem;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
}

:deep(.btn-primary:hover) {
  background: linear-gradient(135deg, #0284c7, #075985);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}
</style>  