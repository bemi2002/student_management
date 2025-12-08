<script setup>
import { computed, ref, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

// Inertia page props
const page = usePage()
const authUser = computed(() => page.props.auth?.user ?? { name: 'Guest', email: '-' })
const userRoles = computed(() => page.props.auth?.roles ?? [])

// Sidebar + Dark Mode
const sidebarOpen = ref(true)
const darkMode = ref(false)

// Sidebar links
const roleLinks = [
  { name: 'Dashboard', route: 'dashboard', roles: ['Admin','Teacher','Student'] },
  { name: 'Chats', route: 'chat.index', roles: ['Admin','Teacher','Student'] },
  { name: 'Courses', route: 'courses.index', roles: ['Admin','Teacher'] },
  { name: 'Teacher Chats', route: 'teacher.chat', roles: ['Teacher'] },
  { name: 'Course Type', route: 'course-type.index', roles: ['Admin','Teacher'] },
  { name: 'Enrollments', route: 'enrollmentss.index', roles: ['Admin'] },
  { name: 'Users', route: 'users.index', roles: ['Admin'] },
  { name: 'Students', route: 'students.index', roles: ['Admin'] },
  { name: 'Settings', route: 'settings.index', roles: ['Admin'] },
  { name: 'Roles', route: 'roles.index', roles: ['Admin'] },
  { name: 'Company Addresses', route: 'company-addresses.index', roles: ['Admin'] },
  { name: 'Locations', route: 'locations.index', roles: ['Admin'] },
]

const filteredLinks = computed(() =>
  roleLinks.filter(link =>
    link.roles.some(role => userRoles.value.includes(role))
  )
)

const groupedLinks = computed(() => {
  const groups = {
    'Quick Access': [],
    'Learning Management': [],
    'User Management': [],
    'System Administration': [],
    'Settings': []
  }

  filteredLinks.value.forEach(link => {
    if (['Dashboard', 'Chats'].includes(link.name)) groups['Quick Access'].push(link)
    else if (['Enrollments'].includes(link.name)) groups['Learning Management'].push(link)
    else if (['Teachers', 'Students'].includes(link.name)) groups['User Management'].push(link)
    else if (['Permissions', 'Admins'].includes(link.name)) groups['System Administration'].push(link)
    else if(['Settings'].includes(link.name)) groups['Settings'].push(link)
  })

  return Object.entries(groups).filter(([_, links]) => links.length > 0)
})

// Dark Mode
const toggleDarkMode = () => {
  darkMode.value = !darkMode.value
  localStorage.setItem('darkMode', darkMode.value)
  updateTheme()
}

const updateTheme = () => {
  if (darkMode.value) {
    document.documentElement.classList.add('dark')
    document.body.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
    document.body.classList.remove('dark')
  }
}

onMounted(() => {
  const saved = localStorage.getItem('darkMode')
  if (saved !== null) darkMode.value = saved === 'true'
  else darkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
  updateTheme()
})

if (typeof window !== 'undefined') {
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('darkMode')) {
      darkMode.value = e.matches
      updateTheme()
    }
  })
}
</script>

<template>
  <div class="flex h-screen bg-[#F5F4FF] dark:bg-gray-900 font-bold transition-colors duration-300">

    <!-- SIDEBAR -->
    <aside 
      :class="[ 
        'flex flex-col transition-all duration-300 ease-in-out shadow-2xl backdrop-blur-xl',
        sidebarOpen ? 'w-72' : 'w-20'
      ]"
      class="bg-gradient-to-b from-purple-500 to-blue-600 dark:from-gray-900 dark:to-gray-800"
    >

      <!-- LOGO -->
      <div class="flex items-center justify-between h-24 px-6 border-b border-white/20 dark:border-gray-700/50">
        <div class="flex items-center space-x-4" v-if="sidebarOpen">
          <div class="w-14 h-14 rounded-full bg-white shadow-2xl overflow-hidden border-2 border-purple-300">
            <img 
              src="https://tse3.mm.bing.net/th/id/OIP.pd__TJLp7WE9gvkK3ZF5xAHaFm"
              class="w-full h-full object-cover"
            />
          </div>
          <div>
            <h1 class="text-white font-extrabold text-xl tracking-wide">Student</h1>
            <h1 class="text-white font-extrabold text-xl tracking-wide">Management</h1>
            <p class="text-purple-100 text-xs">System</p>
          </div>
        </div>

        <button 
          @click="sidebarOpen = !sidebarOpen"
          v-if="sidebarOpen"
          class="p-2 rounded-lg hover:bg-purple-600/40 text-white transition"
        >←</button>

        <div v-else class="flex items-center justify-center">
          <div class="w-12 h-12 rounded-full bg-white overflow-hidden border-2 border-purple-300 shadow-lg">
            <img 
              src="https://tse3.mm.bing.net/th/id/OIP.pd__TJLp7WE9gvkK3ZF5xAHaFm"
              class="w-full h-full object-cover"
            />
          </div>
        </div>
      </div>

      <!-- NAVIGATION -->
      <nav class="flex-1 overflow-y-auto py-6">
        <ul class="space-y-4 px-3">
          <template v-for="[groupName, links] in groupedLinks" :key="groupName">

            <li v-if="sidebarOpen" class="px-3 py-2">
              <p class="text-purple-100 dark:text-gray-400 text-xs font-extrabold tracking-widest uppercase">
                {{ groupName }}
              </p>
            </li>

            <li v-for="link in links" :key="link.name">
              <Link
                :href="route(link.route)"
                :class="[ 
                  'flex items-center rounded-xl transition-all duration-200 group font-bold shadow-sm hover:shadow-lg',
                  sidebarOpen ? 'px-4 py-3' : 'px-3 py-3 justify-center'
                ]"
                class="text-white bg-white/10 hover:bg-white/20 dark:hover:bg-gray-700 backdrop-blur-md"
              >
                <span v-if="sidebarOpen" class="flex-1 font-semibold tracking-wide">{{ link.name }}</span>

                <div v-if="!sidebarOpen" 
                  class="absolute left-full ml-2 px-2 py-1 bg-purple-500 dark:bg-gray-700 
                         text-white text-sm rounded opacity-0 group-hover:opacity-100 shadow-xl whitespace-nowrap">
                  {{ link.name }}
                </div>
              </Link>
            </li>

          </template>
        </ul>
      </nav>

      <!-- PROFILE + TOGGLE -->
      <div class="border-t border-purple-300/30 dark:border-gray-700/60 p-4">

        

        <!-- PROFILE -->
        <div v-if="sidebarOpen" class="flex items-center space-x-4">
          <div class="w-11 h-11 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center shadow-xl">
            <span class="text-purple-700 dark:text-gray-300 font-extrabold text-sm">
              {{ authUser.name.charAt(0).toUpperCase() }}
            </span>
          </div>

          <div class="flex-1">
            <div class="text-white font-bold text-sm truncate tracking-wide">{{ authUser.name }}</div>
            <div class="text-purple-100 dark:text-gray-300 text-xs truncate">{{ authUser.email }}</div>

            <div class="flex space-x-1 mt-2">
              <span 
                v-for="role in userRoles" 
                :key="role"
                class="px-2 py-0.5 bg-purple-600/70 text-white rounded-full text-xs font-semibold shadow-sm"
              >
                {{ role }}
              </span>
            </div>
          </div>
        </div>

        <div v-else class="flex justify-center">
          <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center shadow">
            <span class="text-purple-600 text-xs font-bold">
              {{ authUser.name.charAt(0).toUpperCase() }}
            </span>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div :class="sidebarOpen ? 'flex space-x-3 mt-4' : 'flex flex-col space-y-2 mt-4'">
          <Link
            :href="route('profile.edit')"
            class="bg-white/20 dark:bg-gray-700/50 text-white rounded-lg px-3 py-2 font-semibold 
                   hover:bg-purple-500/40 shadow-md flex justify-center"
          >
            <span v-if="sidebarOpen">Profile</span>
            <span v-else>P</span>
          </Link>

          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="bg-white/20 dark:bg-gray-700/50 text-white rounded-lg px-3 py-2 font-semibold 
                   hover:bg-purple-500/40 shadow-md flex justify-center"
          >
            <span v-if="sidebarOpen">Logout</span>
            <span v-else>L</span>
          </Link>
        </div>
      </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 overflow-y-auto p-10">
      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-purple-200 dark:border-gray-700 p-10">
        <slot />
      </div>
    </main>
  </div>
</template>

<style scoped>
nav::-webkit-scrollbar {
  width: 4px;
}
nav::-webkit-scrollbar-track {
  background: #e5d9ff;
}
nav::-webkit-scrollbar-thumb {
  background: #6d28d9;
  border-radius: 2px;
}
nav::-webkit-scrollbar-thumb:hover {
  background: #5b21b6;
}
</style>

<style>
body {
  background-color: #F5F4FF;
  transition: background-color 0.3s ease;
}
body.dark {
  background-color: #111827;
}

* {
  transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}
</style>
