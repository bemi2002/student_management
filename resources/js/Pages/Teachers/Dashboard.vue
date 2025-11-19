<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const sidebarOpen = ref(true);

const menuItems = [
    { name: 'My Classes', route: '/teacher/classes', icon: '📚' },
    { name: 'Course Resources', route: '/teacher/resources', icon: '📂' },
    { name: 'Student Info', route: '/teacher/students', icon: '👩‍🎓' },
    { name: 'Teacher profile', route: '/teacher/students', icon: '👩‍🎓' },

];
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-green-800">Teacher Dashboard</h2>
                <button 
                    class="md:hidden px-2 py-1 bg-green-200 text-green-800 rounded" 
                    @click="sidebarOpen = !sidebarOpen"
                >
                    ☰
                </button>
            </div>
        </template>

        <div class="flex flex-col md:flex-row min-h-screen">
            <!-- Sidebar -->
            <aside 
                class="w-64 bg-green-50 shadow-md md:block" 
                :class="{ 'hidden': !sidebarOpen }"
            >
                <div class="p-6 font-bold text-xl text-green-800 border-b border-green-200">
                    Teacher Portal
                </div>
                <nav class="mt-4">
                    <ul>
                        <li v-for="item in menuItems" :key="item.name" class="my-2">
                            <Link 
                                :href="item.route" 
                                class="flex items-center gap-3 px-4 py-2 text-green-700 hover:bg-green-100 rounded transition"
                            >
                                <span>{{ item.icon }}</span>
                                <span>{{ item.name }}</span>
                            </Link>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 bg-green-50">
                <!-- Welcome Section -->
                <div class="mb-6 p-6 bg-white shadow rounded-lg">
                    <h3 class="text-2xl font-semibold text-green-800 mb-2">Welcome, Teacher!</h3>
                    <p class="text-gray-700">Access your classes, resources, and student information from this dashboard.</p>
                </div>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded shadow hover:shadow-lg transition">
                        <h4 class="text-lg font-semibold text-green-700 mb-2">My Classes</h4>
                        <p class="text-gray-700">0</p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded shadow hover:shadow-lg transition">
                        <h4 class="text-lg font-semibold text-green-700 mb-2">Course Resources</h4>
                        <p class="text-gray-700">0</p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded shadow hover:shadow-lg transition">
                        <h4 class="text-lg font-semibold text-green-700 mb-2">Student Info</h4>
                        <p class="text-gray-700">0</p>
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
