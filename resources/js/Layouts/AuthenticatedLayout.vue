<script setup>
import { computed, ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const authUser = computed(() => page.props.auth?.user ?? { 
  name: 'Guest', 
  email: '-',
  id: null 
})

const userRoles = computed(() => {
  const user = page.props.auth?.user
  return user?.roles ? user.roles.map(role => role.name) : []
})

const userPermissions = computed(() => {
  const user = page.props.auth?.user
  if (user?.permissions) {
    return user.permissions.map(permission => permission.name)
  }
  return []
})

const sidebarOpen = ref(true)
const darkMode = ref(false)

const hasPermission = (permissionName) => {
  return userPermissions.value.includes(permissionName)
}

const hasRole = (roleName) => {
  return userRoles.value.includes(roleName)
}

const navLinks = computed(() => {
  const baseLinks = [
    { name: 'Dashboard', route: 'dashboard', show: true }
  ]

  if (hasPermission('view chat') || userRoles.value.length > 0) {
    baseLinks.push({ name: 'Chats', route: 'chat.index', show: true })
  }

  if (hasPermission('view enrollments') || hasRole('Admin')) {
    baseLinks.push({ name: 'Enrollments', route: 'enrollmentss.index', show: true })
  }

  if (hasPermission('view students') || hasRole('Admin')) {
    baseLinks.push({ name: 'Students', route: 'students.index', show: true })
  }

  if (hasPermission('view settings') || hasRole('Admin')) {
    baseLinks.push({ name: 'Settings', route: 'settings.index', show: true })
  }

  return baseLinks
})

const groupedLinks = computed(() => {
  const groups = { '': [] }
  navLinks.value.forEach(link => groups[''].push(link))
  return Object.entries(groups)
})

onMounted(() => {
  if (window.innerWidth < 1024) {
    sidebarOpen.value = false
  }
})
</script>

<template>
  <div class="flex h-screen bg-gradient-to-br from-sky-50 to-blue-50 dark:bg-gray-900">

    <!-- MOBILE OVERLAY -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
      :class="[
        'fixed lg:static inset-y-0 left-0 z-40 flex flex-col shadow-2xl backdrop-blur-2xl border-r border-white/20 dark:border-gray-800 transition-all duration-300',
        sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 lg:w-24'
      ]"
      class="bg-gradient-to-b from-sky-600 to-blue-500 dark:from-gray-900/95 dark:to-gray-800/95"
    >

      <!-- LOGO + TOGGLE -->
      <div class="flex items-center justify-between h-20 px-6 border-b border-white/10">
        <div v-if="sidebarOpen" class="flex items-center space-x-4">
          <div class="w-14 h-14 rounded-2xl bg-white shadow-xl overflow-hidden">
            <img
              src="https://tse3.mm.bing.net/th/id/OIP.pd__TJLp7WE9gvkK3ZF5xAHaFm"
              class="w-full h-full object-cover"
            />
          </div>
          <div>
            <h1 class="text-white font-extrabold text-xl">Student</h1>
            <h1 class="text-white font-extrabold text-xl">Management</h1>
          </div>
        </div>

        <button
          @click="sidebarOpen = !sidebarOpen"
          class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white"
        >
          ☰
        </button>
      </div>

      <!-- NAV -->
      <nav class="flex-1 overflow-y-auto py-6">
        <ul class="space-y-3 px-4">
          <li v-for="link in navLinks" :key="link.name">
            <Link
              :href="route(link.route)"
              class="flex items-center rounded-xl px-5 py-3 text-white bg-white/10 hover:bg-white/20 transition"
            >
              <span v-if="sidebarOpen">{{ link.name }}</span>
            </Link>
          </li>
        </ul>
      </nav>

      <!-- PROFILE + ACTIONS (ADDED) -->
      <div class="border-t border-white/10 p-4 space-y-3">

        <!-- USER INFO -->
        <div v-if="sidebarOpen" class="text-white text-sm">
          <div class="font-bold truncate">{{ authUser.name }}</div>
          <div class="text-xs text-sky-200 truncate">{{ authUser.email }}</div>
        </div>

        <!-- ACTION BUTTONS -->
        <div :class="sidebarOpen ? 'flex gap-2' : 'flex flex-col gap-2'">

          <!-- PROFILE -->
          <Link
            v-if="authUser.id"
            :href="route('profile.edit')"
            class="bg-white/20 hover:bg-white/30 text-white rounded-xl px-4 py-2 text-sm font-semibold text-center"
          >
            <span v-if="sidebarOpen">Profile</span>
            <span v-else>P</span>
          </Link>

          <!-- LOGOUT -->
          <Link
            v-if="authUser.id"
            :href="route('logout')"
            method="post"
            as="button"
            class="bg-red-500 hover:bg-red-600 text-white rounded-xl px-4 py-2 text-sm font-semibold text-center"
          >
            <span v-if="sidebarOpen">Logout</span>
            <span v-else>L</span>
          </Link>

          <!-- LOGIN (GUEST) -->
          <Link
            v-if="!authUser.id"
            :href="route('login')"
            class="bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl px-4 py-2 text-sm font-semibold text-center"
          >
            <span v-if="sidebarOpen">Login</span>
            <span v-else>L</span>
          </Link>

        </div>
      </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 overflow-y-auto p-4 lg:p-10">
      <!-- HAMBURGER ICON FOR MOBILE (ADDED THIS) -->
      <button
        v-if="!sidebarOpen"
        @click="sidebarOpen = true"
        class="lg:hidden fixed top-6 left-6 z-50 p-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white shadow-2xl transition-all"
      >
        ☰
      </button>

      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 lg:p-10">
        <slot />
      </div>
    </main>

  </div>
</template>

<style scoped>
nav::-webkit-scrollbar { width: 4px; }
nav::-webkit-scrollbar-thumb { background: #0ea5e9; border-radius: 2px; }
</style>

<style>
body.dark { background-color: #0f172a; }
</style>