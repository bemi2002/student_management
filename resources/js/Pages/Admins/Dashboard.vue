
 <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const sidebarOpen = ref(true);

// Admin sidebar menu items
const menuItems = [
  { name: 'Dashboard', route: '/admin/dashboard', icon: '🏠' },
  { name: 'Students', route: '/admin/students', icon: '👩‍🎓' },
  { name: 'Teachers', route: '/admin/teachers', icon: '👨‍🏫' },
  { name: 'Admins', route: '/admin/admins', icon: '🛡️' },
  { name: 'Courses', route: '/admin/courses', icon: '📚' },
  { name: 'Batches', route: '/admin/batches', icon: '📅' },
  { name: 'Locations', route: '/admin/locations', icon: '📍' },
  { name: 'Enrollments', route: '/admin/enrollments', icon: '📝' },
  { name: 'Resources', route: '/admin/resources', icon: '📂' },
];

// Admin dashboard cards
const cards = [
  { title: 'Students', count: 0},
  { title: 'Teachers', count: 0 },
  { title: 'Admins', count: 0 },
  { title: 'Courses', count: 0 },
  { title: 'Specific Courses', count: 0 },
  { title: 'Batches', count: 0},
  { title: 'Locations', count: 0 },
  { title: 'Enrollments', count: 0 },
  { title: 'Student Enrollments', count: 0 },
  { title: 'Teacher Enrollments', count: 0 },
  { title: 'Resources', count: 0 },
];
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Admin Dashboard" />

    <div class="flex min-h-screen bg-green-50">
      <!-- Sidebar -->
      <aside class="w-64 bg-green-100 shadow-md p-4" :class="{ 'hidden': !sidebarOpen }">
        <div class="font-bold text-xl text-green-800 mb-6 border-b border-green-200 pb-4">Admin Portal</div>
        <nav>
          <ul>
            <li v-for="item in menuItems" :key="item.name" class="my-2">
              <Link
                :href="item.route"
                class="flex items-center gap-3 px-4 py-2 text-green-700 hover:bg-green-200 rounded transition"
              >
                <span>{{ item.icon }}</span>
                <span>{{ item.name }}</span>
              </Link>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-6">
        <!-- Page Header -->
        <div class="bg-white shadow p-6 mb-6 rounded-lg border-l-4 border-green-400">
          <h1 class="text-2xl font-bold text-green-800">Admin Dashboard</h1>
          <p class="text-green-700 mt-2">Welcome, Admin! Manage students, teachers, and courses here.</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="card in cards"
            :key="card.title"
            class="bg-white border-l-4 border-green-400 rounded-lg p-6 shadow hover:shadow-lg transition"
          >
            <h3 class="text-xl font-semibold text-green-800">{{ card.title }}</h3>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ card.count }}</p>
          </div>
        </div>
      </main>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
aside {
  transition: all 0.3s ease-in-out;
}
</style>
