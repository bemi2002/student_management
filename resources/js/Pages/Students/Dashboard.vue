<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const sidebarOpen = ref(true);

// Updated menu items for Student
const menuItems = [
    { name: 'My Courses', route: '/students/courses', icon: '📚' },
    { name: 'My Teachers', route: '/students/teachers', icon: '👩‍🏫' },
    { name: 'Resources', route: '/students/resources', icon: '🗂️' },
    { name: 'Location', route: '/students/location', icon: '📍' },
    { name: 'My Profile', route: '/students/profile', icon: '👤' },
];
</script>

<template>
    <Head title="Student Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Student Dashboard</h2>
                <button 
                    class="md:hidden px-2 py-1 bg-gray-200 rounded" 
                    @click="sidebarOpen = !sidebarOpen"
                >
                    ☰
                </button>
            </div>
        </template>

        <div class="flex flex-col md:flex-row py-12">
            <!-- Sidebar -->
            <aside 
                class="w-64 bg-white shadow-md md:block" 
                :class="{ 'hidden': !sidebarOpen }"
            >
                <div class="p-6 font-bold text-lg border-b">
                    
                </div>
                <nav class="mt-4">
                    <ul>
                        <li v-for="item in menuItems" :key="item.name" class="my-2">
                            <Link 
                                :href="item.route" 
                                class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-200 rounded"
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
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome to your dashboard! Select an option from the sidebar to get started.
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Smooth transition for sidebar toggle */
aside {
    transition: all 0.3s ease-in-out;
}
</style>



