<script setup>
import { ref } from "vue"
import Layout from '@/Layouts/AuthenticatedLayout.vue'
import CompanyAddressesTable from "@/Pages/CompanyAddresses/Index.vue"
import CoursesTable from "@/Pages/Courses/Index.vue"
import CourseTypeTable from "@/Pages/CourseType/Index.vue"
import LocationsTable from "@/Pages/Locations/Index.vue"
import RolesTable from "@/Pages/Roles/Index.vue"
import UsersTable from "@/Pages/Users/Index.vue"

const props = defineProps({
  companies: Array,
  courses: Array,
  courseType: Array,
  locations: Array,
  roles: Array,
  students: Array,
  users: Array,
})

const open = ref({
  companies: false,
  courses: false,
  courseType: false,
  locations: false,
  roles: false,
  students: false,
  users: false,
})
</script>

<template>
  <Layout>
    <div class="max-w-7xl mx-auto p-10">
      <h1 class="text-3xl font-bold mb-8">⚙️ Settings</h1>

      <div class="space-y-4">
        <!-- Companies Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.companies = !open.companies"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Companies
            <span>{{ open.companies ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.companies" class="p-4 bg-white border-t">
            <CompanyAddressesTable :companies="props.companies" />
          </div>
        </div>

        <!-- Course Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.courses = !open.courses"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Courses
            <span>{{ open.courses ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.courses" class="p-4 bg-white border-t">
            <CoursesTable :courses="props.courses" />
          </div>
        </div>

        <!-- Course Type Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.courseType = !open.courseType"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Course Type
            <span>{{ open.courseType ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.courseType" class="p-4 bg-white border-t">
            <!-- CHANGE THIS LINE: from :courseType to :items -->
            <CourseTypeTable :items="props.courseType" />
          </div>
        </div>

        <!-- Locations Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.locations = !open.locations"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Locations
            <span>{{ open.locations ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.locations" class="p-4 bg-white border-t">
            <LocationsTable :locations="props.locations" />
          </div>
        </div>

        <!-- Roles Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.roles = !open.roles"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Roles
            <span>{{ open.roles ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.roles" class="p-4 bg-white border-t">
            <RolesTable :roles="props.roles" />
          </div>
        </div>

        <!-- Users Section -->
        <div class="border rounded-lg overflow-hidden">
          <button 
            @click="open.users = !open.users"
            class="w-full bg-gray-100 px-5 py-3 font-semibold flex justify-between hover:bg-gray-200 transition-colors"
          >
            Users
            <span>{{ open.users ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.users" class="p-4 bg-white border-t">
            <UsersTable :users="props.users" />
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>