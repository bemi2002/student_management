<script setup>
import { computed, ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const authUser = computed(() => page.props.auth?.user ?? { 
  name: 'Guest', 
  email: '-',
  id: null 
})

// Get user roles from the authenticated user object
const userRoles = computed(() => {
  const user = page.props.auth?.user
  return user?.roles ? user.roles.map(role => role.name) : []
})

// Get user permissions from the authenticated user object
const userPermissions = computed(() => {
  const user = page.props.auth?.user
  if (user?.permissions) {
    return user.permissions.map(permission => permission.name)
  }
  return []
})

const sidebarOpen = ref(true)
const darkMode = ref(false)

// Check if user has specific permission
const hasPermission = (permissionName) => {
  return userPermissions.value.includes(permissionName)
}

// Check if user has any of multiple permissions
const hasAnyPermission = (permissionNames) => {
  return permissionNames.some(permission => userPermissions.value.includes(permission))
}

// Check if user has specific role
const hasRole = (roleName) => {
  return userRoles.value.includes(roleName)
}

// Dynamic links based on user's permissions/roles
const navLinks = computed(() => {
  const baseLinks = [
    { name: 'Dashboard', route: 'dashboard', show: true }
  ]

  // Only show Chats for users with chat permission or any role
  if (hasPermission('view chat') || userRoles.value.length > 0) {
    baseLinks.push({ name: 'Chats', route: 'chat.index', show: true })
  }

  // Course-related permissions
  if (hasAnyPermission(['view Courses', 'create Courses', 'edit Courses', 'delete Courses']) || hasRole('Teacher') || hasRole('Admin')) {
    baseLinks.push({ name: 'Courses', route: 'courses.index', show: true })
  }

  // Course Type permissions
  if (hasAnyPermission(['view course_type', 'create course_type', 'Edit course_type', 'delete course_type']) || hasRole('Admin') || hasRole('Teacher')) {
    baseLinks.push({ name: 'Course Type', route: 'course-type.index', show: true })
  }

  // Enrollments - show if user has ANY enrollment permission
  if (hasAnyPermission(['view Enrollments', 'create Enrollments', 'edit Enrollments', 'delete Enrollments']) || hasRole('Admin')) {
    baseLinks.push({ name: 'Enrollments', route: 'enrollments.index', show: true })
  }

  // Users - note: this permission doesn't exist in your seeder
  // You might want to add it or remove this check
  if (hasPermission('view users') || hasRole('Admin')) {
    baseLinks.push({ name: 'Users', route: 'users.index', show: true })
  }

  // Students - show if user has ANY student permission
  if (hasAnyPermission(['view Students', 'create Students', 'edit Students', 'delete Students']) || hasRole('Admin')) {
    baseLinks.push({ name: 'Students', route: 'students.index', show: true })
  }

  // Teachers - show if user has ANY teacher permission
  if (hasAnyPermission(['view Teachers', 'create Teachers', 'edit Teachers', 'delete Teachers']) || hasRole('Admin')) {
    baseLinks.push({ name: 'Teachers', route: 'teachers.index', show: true })
  }

  // System administration
  if (hasPermission('view settings') || hasRole('Admin')) {
    baseLinks.push({ name: 'Settings', route: 'settings.index', show: true })
  }

  // Roles - note: this permission doesn't exist in your seeder
  // You might want to add it or remove this check
  if (hasPermission('view roles') || hasRole('Admin')) {
    baseLinks.push({ name: 'Roles', route: 'roles.index', show: true })
  }

  // Company Addresses - show if user has ANY company address permission
  if (hasAnyPermission(['view Company Addresses', 'create Company Addresses', 'edit Company Addresses', 'delete Company Addresses']) || hasRole('Admin')) {
    baseLinks.push({ name: 'Company Addresses', route: 'company-addresses.index', show: true })
  }

  // Locations - show if user has ANY location permission
  if (hasAnyPermission(['view Locations', 'create Locations', 'edit Locations', 'delete Locations']) || hasRole('Admin')) {
    baseLinks.push({ name: 'Locations', route: 'locations.index', show: true })
  }

  return baseLinks
})

const groupedLinks = computed(() => {
  const groups = {
    'Main Navigation': [],
    'Student Management': [],
    'Course Management': [],
    'System Administration': []
  }

  navLinks.value.forEach(link => {
    if (['Dashboard', 'Chats'].includes(link.name)) {
      groups['Main Navigation'].push(link)
    } else if (['Students', 'Teachers', 'Enrollments'].includes(link.name)) {
      groups['Student Management'].push(link)
    } else if (['Courses', 'Course Type'].includes(link.name)) {
      groups['Course Management'].push(link)
    } else if (['Users', 'Roles', 'Settings', 'Company Addresses', 'Locations'].includes(link.name)) {
      groups['System Administration'].push(link)
    }
  })

  return Object.entries(groups).filter(([_, links]) => links.length > 0)
})

const toggleDarkMode = () => {
  darkMode.value = !darkMode.value
  localStorage.setItem('darkMode', darkMode.value)
  updateTheme()
}

const updateTheme = () => {
  document.documentElement.classList.toggle('dark', darkMode.value)
  document.body.classList.toggle('dark', darkMode.value)
}

onMounted(() => {
  const saved = localStorage.getItem('darkMode')
  darkMode.value = saved ? saved === 'true' : window.matchMedia('(prefers-color-scheme: dark)').matches
  updateTheme()
})
</script>

<template>
  <div class="flex h-screen bg-gradient-to-br from-sky-50 to-blue-50 dark:bg-gray-900 transition-colors duration-300 font-semibold">

    <!-- SIDEBAR -->
    <aside
      :class="[
        'flex flex-col shadow-2xl backdrop-blur-2xl border-r border-white/20 dark:border-gray-800 transition-all duration-300',
        sidebarOpen ? 'w-72' : 'w-24'
      ]"
      class="bg-gradient-to-b from-sky-600 to-blue-500 dark:from-gray-900/95 dark:to-gray-800/95"
    >

      <!-- LOGO -->
      <div class="flex items-center justify-between h-24 px-6 border-b border-white/10">
        <div v-if="sidebarOpen" class="flex items-center space-x-4">
          <div class="w-16 h-16 rounded-2xl bg-white shadow-xl overflow-hidden border-4 border-sky-300/50">
            <img
              src="https://tse3.mm.bing.net/th/id/OIP.pd__TJLp7WE9gvkK3ZF5xAHaFm"
              class="w-full h-full object-cover"
            />
          </div>

          <div>
            <h1 class="text-white font-extrabold text-2xl tracking-wide drop-shadow-md">Student</h1>
            <h1 class="text-white font-extrabold text-2xl tracking-wide drop-shadow-md">Management</h1>
            <p class="text-sky-200 text-xs opacity-80 tracking-widest">SYSTEM</p>
          </div>
        </div>

        <!-- Collapse -->
        <button
          @click="sidebarOpen = !sidebarOpen"
          class="p-2 rounded-xl bg-white/10 hover:bg-white/20 dark:hover:bg-gray-700 text-white shadow-lg"
        >
          {{ sidebarOpen ? '←' : '→' }}
        </button>
      </div>

      <!-- NAVIGATION -->
      <nav class="flex-1 overflow-y-auto py-6">
        <ul class="space-y-5 px-4">
          <template v-for="[groupName, links] in groupedLinks" :key="groupName">
            <li v-if="sidebarOpen && links.length" class="px-3">
              <p class="text-sky-200 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">
                {{ groupName }}
              </p>
            </li>

            <li v-for="link in links" :key="link.name">
              <Link
                :href="route(link.route)"
                :class="[
                  'flex items-center rounded-xl transition-all duration-200 group shadow-md hover:shadow-xl relative',
                  sidebarOpen ? 'px-5 py-3' : 'px-4 py-3 justify-center',
                  'bg-white/10 hover:bg-white/20 dark:bg-gray-700/40 dark:hover:bg-gray-700'
                ]"
                class="text-white backdrop-blur-md"
              >
                <span v-if="sidebarOpen" class="flex-1 font-semibold tracking-wide">
                  {{ link.name }}
                </span>

                <!-- Tooltip -->
                <div
                  v-if="!sidebarOpen"
                  class="absolute left-full ml-3 px-2 py-1 bg-sky-600 text-white text-xs rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50"
                >
                  {{ link.name }}
                </div>
              </Link>
            </li>
          </template>
        </ul>
      </nav>

      <!-- PROFILE -->
      <div class="border-t border-white/10 dark:border-gray-700 p-5 pt-6">
        <div v-if="sidebarOpen" class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-white dark:bg-gray-700 rounded-2xl shadow-xl flex items-center justify-center">
            <span class="text-sky-700 dark:text-gray-200 text-lg font-black">
              {{ authUser.name.charAt(0).toUpperCase() }}
            </span>
          </div>

          <div class="flex-1">
            <div class="text-white font-bold text-sm truncate">
              {{ authUser.name }}
            </div>
            <div class="text-sky-200 dark:text-gray-300 text-xs truncate">
              {{ authUser.email }}
            </div>
            <div class="flex flex-wrap gap-1 mt-2">
              <!-- Show user ID -->
              <span
                class="px-2 py-0.5 bg-gray-600/70 text-white rounded-full text-[10px] font-semibold tracking-wide shadow-sm"
                v-if="authUser.id"
              >
                ID: {{ authUser.id }}
              </span>
              
              <!-- Show user roles -->
              <span
                v-for="role in userRoles"
                :key="role"
                class="px-2 py-0.5 bg-blue-600/70 text-white rounded-full text-[10px] font-semibold tracking-wide shadow-sm"
              >
                {{ role }}
              </span>
              
              <!-- Show permissions count -->
              <span
                class="px-2 py-0.5 bg-green-600/70 text-white rounded-full text-[10px] font-semibold tracking-wide shadow-sm"
                v-if="userPermissions.length > 0"
              >
                {{ userPermissions.length }} perm
              </span>
              
              <!-- Show if guest user -->
              <span
                class="px-2 py-0.5 bg-yellow-600/70 text-white rounded-full text-[10px] font-semibold tracking-wide shadow-sm"
                v-if="!authUser.id"
              >
                Guest
              </span>
            </div>
          </div>
        </div>

        <div v-else class="flex justify-center">
          <div class="w-10 h-10 bg-white dark:bg-gray-700 rounded-xl flex items-center justify-center shadow-lg">
            <span class="text-sky-700 dark:text-gray-200 font-bold text-sm">
              {{ authUser.name.charAt(0).toUpperCase() }}
            </span>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div :class="sidebarOpen ? 'flex space-x-3 mt-6' : 'flex flex-col space-y-3 mt-6'">
          <Link
            :href="route('profile.edit')"
            class="bg-white/20 dark:bg-gray-700/50 text-white rounded-xl px-4 py-2 font-semibold hover:bg-white/30 dark:hover:bg-gray-700 shadow-md text-center"
            v-if="authUser.id"
          >
            <span v-if="sidebarOpen">Profile</span>
            <span v-else>P</span>
          </Link>

          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white rounded-xl px-4 py-2 font-semibold shadow-md text-center"
            v-if="authUser.id"
          >
            <span v-if="sidebarOpen">Logout</span>
            <span v-else>L</span>
          </Link>

          <!-- Show login button for guest users -->
          <Link
            :href="route('login')"
            class="bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white rounded-xl px-4 py-2 font-semibold shadow-md text-center"
            v-if="!authUser.id"
          >
            <span v-if="sidebarOpen">Login</span>
            <span v-else>L</span>
          </Link>
        </div>
      </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 overflow-y-auto p-10">
      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-sky-100/40 dark:border-gray-700 p-10">
        <slot />
      </div>
    </main>

  </div>
</template>

<style scoped>
nav::-webkit-scrollbar { width: 4px; }
nav::-webkit-scrollbar-track { background: #e0f2fe; }
nav::-webkit-scrollbar-thumb {
  background: #0ea5e9;
  border-radius: 2px;
}
nav::-webkit-scrollbar-thumb:hover { background: #0284c7; }
</style>

<style>
body {
  background-color: #f0f9ff;
  transition: 0.3s ease;
}
body.dark {
  background-color: #0f172a;
}
* {
  transition: background-color .3s, border-color .3s, color .3s, box-shadow .3s;
}
</style>