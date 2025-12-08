<script setup>
import { ref, reactive, watch } from "vue"
import Layout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  companies: Array,
  courses: Array,
  courseType: Array,
  locations: Array,
  roles: Array,
  students: Array,
  users: Array,
  permissions: Array,
})

const open = ref({
  companies: false,
  courses: false,
  courseType: false,
  locations: false,
  roles: false,
  users: false,
})

// Use refs instead of reactive for arrays to avoid reactivity issues
const companies = ref([...props.companies])
const courses = ref([...props.courses])
const courseTypes = ref([...props.courseType])
const locations = ref([...props.locations])
const roles = ref([...props.roles])
const users = ref([...props.users.map(u => ({ ...u, showPassword: false }))])

// Watch for prop changes and update local state
watch(() => props.companies, (newCompanies) => {
  companies.value = [...newCompanies]
})

watch(() => props.courses, (newCourses) => {
  courses.value = [...newCourses]
})

watch(() => props.courseType, (newCourseTypes) => {
  courseTypes.value = [...newCourseTypes]
})

watch(() => props.locations, (newLocations) => {
  locations.value = [...newLocations]
})

watch(() => props.roles, (newRoles) => {
  roles.value = [...newRoles]
})

watch(() => props.users, (newUsers) => {
  users.value = [...newUsers.map(u => ({ ...u, showPassword: false }))]
})

// Company CRUD form state
const showCompanyForm = ref(false)
const editingCompany = ref(null)

const companyForm = useForm({
  company_name: '',
  email: '',
  contact_phone: '',
  website: '',
  city: '',
  sub_city: '',
  building_name: '',
  building_number: ''
})

// Course CRUD form state
const showCourseForm = ref(false)
const editingCourse = ref(null)
const courseForm = useForm({
  course_name: '',
  description: '',
  duration_dates: '',
  level: '',
  start_date: '',
  end_date: ''
})

// Course Type CRUD form state
const showCourseTypeForm = ref(false)
const editingCourseType = ref(null)
const courseTypeForm = useForm({
  course_type: '',
  student_capacity: 0
})

// Location CRUD form state
const showLocationForm = ref(false)
const editingLocation = ref(null)
const locationForm = useForm({
  name: ''
})

// Role CRUD form state
const showRoleForm = ref(false)
const editingRole = ref(null)
const roleForm = useForm({
  name: '',
  permissions: []
})

// User CRUD form state
const showUserForm = ref(false)
const editingUser = ref(null)
const userForm = useForm({
  name: '',
  email: '',
  password: '',
  roles: []
})

// Show/hide password for user form
const showPassword = ref(false)

// Toast message state
const toastMessage = ref('')
const showToast = ref(false)
const showToastMessage = (message) => {
  toastMessage.value = message
  showToast.value = true
  setTimeout(() => showToast.value = false, 3000)
}

// Company functions
const editCompany = (company) => {
  editingCompany.value = company
  companyForm.company_name = company.company_name || ''
  companyForm.email = company.email || ''
  companyForm.contact_phone = company.contact_phone || ''
  companyForm.website = company.website || ''
  companyForm.city = company.city || ''
  companyForm.sub_city = company.sub_city || ''
  companyForm.building_name = company.building_name || ''
  companyForm.building_number = company.building_number || ''
  showCompanyForm.value = true
}

const saveCompany = async () => {
  try {
    if (editingCompany.value) {
      await companyForm.put(route('company-addresses.update', editingCompany.value.id), {
        preserveScroll: true,
        onSuccess: () => {
          resetCompanyForm()
          showToastMessage('Company updated successfully!')
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating company. Please check the form.')
        }
      })
    } else {
      await companyForm.post(route('company-addresses.store'), {
        preserveScroll: true,
        onSuccess: () => {
          resetCompanyForm()
          showToastMessage('Company created successfully!')
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating company. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteCompany = async (id) => {
  if (!confirm('Are you sure you want to delete this company?')) return
  
  try {
    await router.delete(route('company-addresses.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        showToastMessage('Company deleted successfully!')
      },
      onError: () => {
        showToastMessage('Error deleting company. Please try again.')
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetCompanyForm = () => {
  showCompanyForm.value = false
  editingCompany.value = null
  companyForm.reset()
  companyForm.clearErrors()
}

const cancelCompanyForm = () => {
  resetCompanyForm()
}

// Course functions
const editCourse = (course) => {
  editingCourse.value = course
  courseForm.course_name = course.course_name
  courseForm.description = course.description
  courseForm.duration_dates = course.duration_dates
  courseForm.level = course.level
  courseForm.start_date = course.start_date
  courseForm.end_date = course.end_date
  showCourseForm.value = true
}

const saveCourse = async () => {
  try {
    if (editingCourse.value) {
      await courseForm.put(route('courses.update', editingCourse.value.id), {
        preserveScroll: true,
        onSuccess: () => {
          resetCourseForm()
          showToastMessage('Course updated successfully!')
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating course. Please check the form.')
        }
      })
    } else {
      await courseForm.post(route('courses.store'), {
        preserveScroll: true,
        onSuccess: () => {
          resetCourseForm()
          showToastMessage('Course created successfully!')
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating course. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteCourse = async (id) => {
  if (!confirm('Are you sure you want to delete this course?')) return
  
  try {
    await router.delete(route('courses.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        showToastMessage('Course deleted successfully!')
      },
      onError: () => {
        showToastMessage('Error deleting course. Please try again.')
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetCourseForm = () => {
  showCourseForm.value = false
  editingCourse.value = null
  courseForm.reset()
  courseForm.clearErrors()
}

const cancelCourseForm = () => {
  resetCourseForm()
}

// Course Type functions
const editCourseType = (courseType) => {
  editingCourseType.value = courseType
  courseTypeForm.course_type = courseType.course_type
  courseTypeForm.student_capacity = courseType.student_capacity
  showCourseTypeForm.value = true
}

const saveCourseType = async () => {
  try {
    if (editingCourseType.value) {
      await courseTypeForm.put(route('course-type.update', editingCourseType.value.id), {
        preserveScroll: true,
        onSuccess: () => {
          resetCourseTypeForm()
          showToastMessage('Course type updated successfully!')
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating course type. Please check the form.')
        }
      })
    } else {
      await courseTypeForm.post(route('course-type.store'), {
        preserveScroll: true,
        onSuccess: () => {
          resetCourseTypeForm()
          showToastMessage('Course type created successfully!')
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating course type. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteCourseType = async (id) => {
  if (!confirm('Are you sure you want to delete this course type?')) return
  
  try {
    await router.delete(route('course-type.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        showToastMessage('Course type deleted successfully!')
      },
      onError: () => {
        showToastMessage('Error deleting course type. Please try again.')
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetCourseTypeForm = () => {
  showCourseTypeForm.value = false
  editingCourseType.value = null
  courseTypeForm.reset()
  courseTypeForm.clearErrors()
}

const cancelCourseTypeForm = () => {
  resetCourseTypeForm()
}

// Location functions
const editLocation = (location) => {
  editingLocation.value = location
  locationForm.name = location.name
  showLocationForm.value = true
}

const saveLocation = async () => {
  try {
    if (editingLocation.value) {
      await locationForm.put(route('locations.update', editingLocation.value.id), {
        preserveScroll: true,
        onSuccess: () => {
          resetLocationForm()
          showToastMessage('Location updated successfully!')
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating location. Please check the form.')
        }
      })
    } else {
      await locationForm.post(route('locations.store'), {
        preserveScroll: true,
        onSuccess: () => {
          resetLocationForm()
          showToastMessage('Location created successfully!')
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating location. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteLocation = async (id) => {
  if (!confirm('Are you sure you want to delete this location?')) return
  
  try {
    await router.delete(route('locations.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        showToastMessage('Location deleted successfully!')
      },
      onError: () => {
        showToastMessage('Error deleting location. Please try again.')
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetLocationForm = () => {
  showLocationForm.value = false
  editingLocation.value = null
  locationForm.reset()
  locationForm.clearErrors()
}

const cancelLocationForm = () => {
  resetLocationForm()
}

// Role functions - FIXED VERSION
const editRole = (role) => {
  editingRole.value = role
  roleForm.name = role.name
  // Fix: Use permission IDs instead of names
  roleForm.permissions = role.permissions ? role.permissions.map(p => p.id) : []
  showRoleForm.value = true
}

const saveRole = async () => {
  try {
    if (editingRole.value) {
      await roleForm.put(route('roles.update', editingRole.value.id), {
        preserveScroll: true,
        onSuccess: () => {
          resetRoleForm()
          showToastMessage('Role updated successfully!')
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating role. Please check the form.')
        }
      })
    } else {
      await roleForm.post(route('roles.store'), {
        preserveScroll: true,
        onSuccess: () => {
          resetRoleForm()
          showToastMessage('Role created successfully!')
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating role. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteRole = async (id, roleName) => {
  // Prevent deletion of protected roles
  const protectedRoles = ['admin', 'super-admin', 'Admin', 'Super-Admin'];
  if (protectedRoles.map(r => r.toLowerCase()).includes(roleName.toLowerCase())) {
    showToastMessage('Protected system roles cannot be deleted.')
    return
  }

  if (!confirm(`Are you sure you want to delete the role "${roleName}" permanently?`)) return
  
  try {
    await router.delete(route('roles.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        showToastMessage(`Role "${roleName}" deleted successfully!`)
      },
      onError: (error) => {
        console.log('Delete error:', error)
        showToastMessage(`Error deleting role "${roleName}". Please try again.`)
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetRoleForm = () => {
  showRoleForm.value = false
  editingRole.value = null
  roleForm.reset()
  roleForm.clearErrors()
}

const cancelRoleForm = () => {
  resetRoleForm()
}

// Check if role is protected (for UI disabling)
const isProtectedRole = (roleName) => {
  const protectedRoles = ['admin', 'super-admin', 'Admin', 'Super-Admin'];
  return protectedRoles.map(r => r.toLowerCase()).includes(roleName.toLowerCase());
}

// User functions - FIXED VERSION
const editUser = (user) => {
  editingUser.value = user
  userForm.name = user.name
  userForm.email = user.email
  userForm.password = '' // Clear password field for edits
  userForm.roles = user.roles ? user.roles.map(r => r.id) : []
  showUserForm.value = true
  showPassword.value = false
}

const saveUser = async () => {
  try {
    if (editingUser.value) {
      await userForm.put(route('users.update', editingUser.value.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          // Reload the users data to reflect changes
          router.reload({ 
            only: ['users'],
            onSuccess: () => {
              resetUserForm()
              showToastMessage('User updated successfully!')
            }
          })
        },
        onError: (errors) => {
          console.log('Update errors:', errors)
          showToastMessage('Error updating user. Please check the form.')
        }
      })
    } else {
      await userForm.post(route('users.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          // Reload the users data to reflect changes
          router.reload({ 
            only: ['users'],
            onSuccess: () => {
              resetUserForm()
              showToastMessage('User created successfully!')
            }
          })
        },
        onError: (errors) => {
          console.log('Create errors:', errors)
          showToastMessage('Error creating user. Please check the form.')
        }
      })
    }
  } catch (error) {
    console.log('Form submission error:', error)
    showToastMessage('An error occurred. Please try again.')
  }
}

const deleteUser = async (id, userName) => {
  if (!confirm(`Are you sure you want to delete the user "${userName}" permanently?`)) return
  
  try {
    await router.delete(route('users.destroy', id), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        // Reload the users data to reflect deletion
        router.reload({ 
          only: ['users'],
          onSuccess: () => {
            showToastMessage(`User "${userName}" deleted successfully!`)
          }
        })
      },
      onError: (error) => {
        console.log('Delete error:', error)
        showToastMessage(`Error deleting user "${userName}". Please try again.`)
      }
    })
  } catch (error) {
    console.log('Delete error:', error)
    showToastMessage('An error occurred while deleting.')
  }
}

const resetUserForm = () => {
  showUserForm.value = false
  editingUser.value = null
  userForm.reset()
  userForm.clearErrors()
  showPassword.value = false
}

const cancelUserForm = () => {
  resetUserForm()
}

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const toggleUserPassword = (user) => {
  user.showPassword = !user.showPassword
}
</script>

<template>
  <Layout>
    <div class="max-w-7xl mx-auto p-10">
      <h1 class="text-3xl font-bold mb-8">⚙️ Settings</h1>

      <!-- Toast message -->
      <transition name="fade">
        <div v-if="showToast" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
          {{ toastMessage }}
        </div>
      </transition>

      <div class="space-y-6">
   <!-- Companies Accordion - UPDATED (Removed: company_name, email, contact_phone, website) -->
<div class="border rounded-lg overflow-hidden">
  <button @click="open.companies = !open.companies" class="w-full bg-gray-100 px-10 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
    Company location <span>{{ open.companies ? "▲" : "▼" }}</span>
  </button>

  <div v-if="open.companies" class="p-12 bg-white border-t">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-semibold"></h2>
      <button 
        @click="showCompanyForm = true; editingCompany = null; companyForm.reset()" 
        class="bg-blue-500 text-white px-4 py-3 rounded hover:bg-blue-600 transition-colors" 
        :disabled="companyForm.processing"
        v-if="!showCompanyForm"
      >
        + Add Company
      </button>
    </div>

    <!-- Company Inline Form - ONLY LOCATION FIELDS -->
    <div v-if="showCompanyForm" class="bg-gray-50 p-12 rounded-lg mb-6 border">
      <h3 class="font-semibold mb-4 text-lg">{{ editingCompany ? 'Edit Company' : 'Create New Company' }}</h3>

      <form @submit.prevent="saveCompany" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

          <!-- Location Fields Only -->
          <div>
            <label class="block text-sm font-medium mb-2">City</label>
            <input 
              v-model="companyForm.city" 
              class="w-full border px-4 py-3 rounded" 
              :disabled="companyForm.processing"
              :class="{ 'border-red-500': companyForm.errors.city }"
            >
            <p v-if="companyForm.errors.city" class="text-red-500 text-xs mt-1">{{ companyForm.errors.city }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Sub City</label>
            <input 
              v-model="companyForm.sub_city" 
              class="w-full border px-4 py-3 rounded" 
              :disabled="companyForm.processing"
              :class="{ 'border-red-500': companyForm.errors.sub_city }"
            >
            <p v-if="companyForm.errors.sub_city" class="text-red-500 text-xs mt-1">{{ companyForm.errors.sub_city }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Building Name</label>
            <input 
              v-model="companyForm.building_name" 
              class="w-full border px-4 py-3 rounded" 
              :disabled="companyForm.processing"
              :class="{ 'border-red-500': companyForm.errors.building_name }"
            >
            <p v-if="companyForm.errors.building_name" class="text-red-500 text-xs mt-1">{{ companyForm.errors.building_name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Building Number</label>
            <input 
              v-model="companyForm.building_number" 
              class="w-full border px-4 py-3 rounded" 
              :disabled="companyForm.processing"
              :class="{ 'border-red-500': companyForm.errors.building_number }"
            >
            <p v-if="companyForm.errors.building_number" class="text-red-500 text-xs mt-1">{{ companyForm.errors.building_number }}</p>
          </div>

        </div>

        <!-- General Errors -->
        <div v-if="companyForm.hasErrors" class="bg-red-50 border border-red-200 rounded p-4">
          <p class="text-red-600 font-medium">Please fix the following errors:</p>
          <ul class="list-disc list-inside text-red-500 text-sm mt-2">
            <li v-for="error in Object.values(companyForm.errors)" :key="error">{{ error }}</li>
          </ul>
        </div>

        <div class="flex gap-3 mt-6 pt-5 border-t">
          <button 
            type="submit" 
            class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
            :disabled="companyForm.processing"
          >
            {{ companyForm.processing ? 'Saving...' : (editingCompany ? 'Update Company' : 'Create Company') }}
          </button>

          <button 
            type="button" 
            @click="cancelCompanyForm" 
            class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
            :disabled="companyForm.processing"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- Companies Card Layout - ONLY SHOW LOCATION -->
    <div v-if="!showCompanyForm" class="space-y-6">
      <div v-for="company in companies" :key="company.id" class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 hover:shadow-md transition-shadow">

        <div class="flex justify-between items-start mb-6">
          <h3 class="text-xl font-bold text-gray-800"></h3>
          
          <div class="flex space-x-3">
            <button 
              @click="editCompany(company)" 
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors text-sm" 
              :disabled="companyForm.processing"
            >
              Edit
            </button>

            <button 
              @click="deleteCompany(company.id)" 
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors text-sm" 
              :disabled="companyForm.processing"
            >
              Delete
            </button>
          </div>
        </div>

        <!-- Location Information Only -->
        <div>
          <h4 class="text-sm font-medium text-gray-500 mb-2">Location Information</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
              <p class="text-gray-700"><span class="font-medium">City:</span> {{ company.city || 'Not provided' }}</p>
              <p class="text-gray-700"><span class="font-medium">Sub City:</span> {{ company.sub_city || 'Not provided' }}</p>
            </div>

            <div>
              <p class="text-gray-700"><span class="font-medium">Building Name:</span> {{ company.building_name || 'Not provided' }}</p>
              <p class="text-gray-700"><span class="font-medium">Building Number:</span> {{ company.building_number || 'Not provided' }}</p>
            </div>

          </div>
        </div>
      </div>

      <div v-if="companies.length === 0" class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <h3 class="mt-4 text-lg font-medium text-gray-900">No company information</h3>
        <p class="mt-2 text-sm text-gray-500">Get started by adding company details.</p>
        <button 
          @click="showCompanyForm = true; editingCompany = null; companyForm.reset()" 
          class="mt-6 inline-flex items-center px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          + Add Company
        </button>
      </div>

    </div>
  </div>
</div>

        <!-- Courses Accordion -->
        <div class="border rounded-lg overflow-hidden">
          <button @click="open.courses = !open.courses" class="w-full bg-gray-100 px-9 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
            Courses <span>{{ open.courses ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.courses" class="p-6 bg-white border-t">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold">Manage Courses</h2>
              <button 
                @click="showCourseForm = true; editingCourse = null; courseForm.reset()" 
                class="bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition-colors" 
                :disabled="courseForm.processing"
                v-if="!showCourseForm"
              >
                + Add New Course
              </button>
            </div>

            <!-- Course Inline Form -->
            <div v-if="showCourseForm" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <h3 class="font-semibold mb-4 text-lg">{{ editingCourse ? 'Edit Course' : 'Create New Course' }}</h3>
              <form @submit.prevent="saveCourse" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-sm font-medium mb-2">Course Name *</label>
                    <input 
                      v-model="courseForm.course_name" 
                      class="w-full border px-4 py-3 rounded" 
                      required 
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.course_name }"
                    >
                    <p v-if="courseForm.errors.course_name" class="text-red-500 text-xs mt-1">{{ courseForm.errors.course_name }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Level</label>
                    <select 
                      v-model="courseForm.level" 
                      class="w-full border px-4 py-3 rounded"
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.level }"
                    >
                      <option value="">Select Level</option>
                      <option value="beginner">Beginner</option>
                      <option value="intermediate">Intermediate</option>
                      <option value="advanced">Advanced</option>
                    </select>
                    <p v-if="courseForm.errors.level" class="text-red-500 text-xs mt-1">{{ courseForm.errors.level }}</p>
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea 
                      v-model="courseForm.description" 
                      class="w-full border px-4 py-3 rounded" 
                      rows="3"
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.description }"
                    ></textarea>
                    <p v-if="courseForm.errors.description" class="text-red-500 text-xs mt-1">{{ courseForm.errors.description }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Duration (days)</label>
                    <input 
                      v-model="courseForm.duration_dates" 
                      type="number" 
                      class="w-full border px-4 py-3 rounded" 
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.duration_dates }"
                    >
                    <p v-if="courseForm.errors.duration_dates" class="text-red-500 text-xs mt-1">{{ courseForm.errors.duration_dates }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Start Date</label>
                    <input 
                      v-model="courseForm.start_date" 
                      type="date" 
                      class="w-full border px-4 py-3 rounded" 
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.start_date }"
                    >
                    <p v-if="courseForm.errors.start_date" class="text-red-500 text-xs mt-1">{{ courseForm.errors.start_date }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">End Date</label>
                    <input 
                      v-model="courseForm.end_date" 
                      type="date" 
                      class="w-full border px-4 py-3 rounded" 
                      :disabled="courseForm.processing"
                      :class="{ 'border-red-500': courseForm.errors.end_date }"
                    >
                    <p v-if="courseForm.errors.end_date" class="text-red-500 text-xs mt-1">{{ courseForm.errors.end_date }}</p>
                  </div>
                </div>

                <!-- General form errors -->
                <div v-if="courseForm.hasErrors" class="bg-red-50 border border-red-200 rounded p-4">
                  <p class="text-red-600 font-medium">Please fix the following errors:</p>
                  <ul class="list-disc list-inside text-red-500 text-sm mt-2">
                    <li v-for="error in Object.values(courseForm.errors)" :key="error">{{ error }}</li>
                  </ul>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t">
                  <button 
                    type="submit" 
                    class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
                    :disabled="courseForm.processing"
                  >
                    {{ courseForm.processing ? 'Saving...' : (editingCourse ? 'Update Course' : 'Create Course') }}
                  </button>
                  <button 
                    type="button" 
                    @click="cancelCourseForm" 
                    class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
                    :disabled="courseForm.processing"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>

            <!-- Courses Table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-5 py-3">ID</th>
                    <th class="border border-gray-300 px-5 py-3">Course Name</th>
                    <th class="border border-gray-300 px-5 py-3">Description</th>
                    <th class="border border-gray-300 px-5 py-3">Duration (days)</th>
                    <th class="border border-gray-300 px-5 py-3">Level</th>
                    <th class="border border-gray-300 px-5 py-3">Start Date</th>
                    <th class="border border-gray-300 px-5 py-3">End Date</th>
                    <th class="border border-gray-300 px-5 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="course in courses" :key="course.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-5 py-3">{{ course.id }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ course.course_name }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ course.description }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ course.duration_dates }}</td>
                    <td class="border border-gray-300 px-5 py-3 capitalize">{{ course.level }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ course.start_date }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ course.end_date }}</td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex space-x-3">
                        <button 
                          @click="editCourse(course)" 
                          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm" 
                          :disabled="courseForm.processing"
                        >
                          Edit
                        </button>
                        <button 
                          @click="deleteCourse(course.id)" 
                          class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm" 
                          :disabled="courseForm.processing"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="courses.length === 0">
                    <td colspan="8" class="border border-gray-300 px-5 py-4 text-center text-gray-500">
                      No courses found. Click "Add New Course" to create one.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Course Types Accordion -->
        <div class="border rounded-lg overflow-hidden">
          <button @click="open.courseType = !open.courseType" class="w-full bg-gray-100 px-10 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
            Course Types <span>{{ open.courseType ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.courseType" class="p-6 bg-white border-t">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold">Manage Course Types</h2>
              <button 
                @click="showCourseTypeForm = true; editingCourseType = null; courseTypeForm.reset()" 
                class="bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition-colors" 
                :disabled="courseTypeForm.processing"
                v-if="!showCourseTypeForm"
              >
                + Add New Course Type
              </button>
            </div>

            <!-- Course Type Inline Form -->
            <div v-if="showCourseTypeForm" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <h3 class="font-semibold mb-4 text-lg">{{ editingCourseType ? 'Edit Course Type' : 'Create New Course Type' }}</h3>
              <form @submit.prevent="saveCourseType" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-sm font-medium mb-2">Course Type *</label>
                    <select 
                      v-model="courseTypeForm.course_type" 
                      class="w-full border px-4 py-3 rounded"
                      required
                      :disabled="courseTypeForm.processing"
                      :class="{ 'border-red-500': courseTypeForm.errors.course_type }"
                    >
                      <option value="">Select Course Type</option>
                      <option value="morning">Morning</option>
                      <option value="evening">Evening</option>
                      <option value="weekend">Weekend</option>
                    </select>
                    <p v-if="courseTypeForm.errors.course_type" class="text-red-500 text-xs mt-1">{{ courseTypeForm.errors.course_type }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Student Capacity *</label>
                    <input 
                      v-model="courseTypeForm.student_capacity" 
                      type="number" 
                      class="w-full border px-4 py-3 rounded" 
                      required
                      min="1"
                      :disabled="courseTypeForm.processing"
                      :class="{ 'border-red-500': courseTypeForm.errors.student_capacity }"
                    >
                    <p v-if="courseTypeForm.errors.student_capacity" class="text-red-500 text-xs mt-1">{{ courseTypeForm.errors.student_capacity }}</p>
                  </div>
                </div>

                <!-- General form errors -->
                <div v-if="courseTypeForm.hasErrors" class="bg-red-50 border border-red-200 rounded p-4">
                  <p class="text-red-600 font-medium">Please fix the following errors:</p>
                  <ul class="list-disc list-inside text-red-500 text-sm mt-2">
                    <li v-for="error in Object.values(courseTypeForm.errors)" :key="error">{{ error }}</li>
                  </ul>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t">
                  <button 
                    type="submit" 
                    class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
                    :disabled="courseTypeForm.processing"
                  >
                    {{ courseTypeForm.processing ? 'Saving...' : (editingCourseType ? 'Update Course Type' : 'Create Course Type') }}
                  </button>
                  <button 
                    type="button" 
                    @click="cancelCourseTypeForm" 
                    class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
                    :disabled="courseTypeForm.processing"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>

            <!-- Course Types Table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-5 py-3">ID</th>
                    <th class="border border-gray-300 px-5 py-3">Course Type</th>
                    <th class="border border-gray-300 px-5 py-3">Student Capacity</th>
                    <th class="border border-gray-300 px-5 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="courseType in courseTypes" :key="courseType.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-5 py-3">{{ courseType.id }}</td>
                    <td class="border border-gray-300 px-5 py-3 capitalize">{{ courseType.course_type }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ courseType.student_capacity }}</td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex space-x-3">
                        <button 
                          @click="editCourseType(courseType)" 
                          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm" 
                          :disabled="courseTypeForm.processing"
                        >
                          Edit
                        </button>
                        <button 
                          @click="deleteCourseType(courseType.id)" 
                          class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm" 
                          :disabled="courseTypeForm.processing"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="courseTypes.length === 0">
                    <td colspan="4" class="border border-gray-300 px-5 py-4 text-center text-gray-500">
                      No course types found. Click "Add New Course Type" to create one.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Locations Accordion -->
        <div class="border rounded-lg overflow-hidden">
          <button @click="open.locations = !open.locations" class="w-full bg-gray-100 px-10 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
            Locations <span>{{ open.locations ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.locations" class="p-6 bg-white border-t">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold">Manage Locations</h2>
              <button 
                @click="showLocationForm = true; editingLocation = null; locationForm.reset()" 
                class="bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition-colors" 
                :disabled="locationForm.processing"
                v-if="!showLocationForm"
              >
                + Add New Location
              </button>
            </div>

            <!-- Location Inline Form -->
            <div v-if="showLocationForm" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <h3 class="font-semibold mb-4 text-lg">{{ editingLocation ? 'Edit Location' : 'Create New Location' }}</h3>
              <form @submit.prevent="saveLocation" class="space-y-5">
                <div>
                  <label class="block text-sm font-medium mb-2">Location Name *</label>
                  <input 
                    v-model="locationForm.name" 
                    class="w-full border px-10 py-14 rounded" 
                    required 
                    :disabled="locationForm.processing"
                    :class="{ 'border-red-500': locationForm.errors.name }"
                    placeholder="Enter location name"
                  >
                  <p v-if="locationForm.errors.name" class="text-red-500 text-xs mt-1">{{ locationForm.errors.name }}</p>
                </div>

                <!-- General form errors -->
                <div v-if="locationForm.hasErrors" class="bg-red-50 border border-red-200 rounded p-4">
                  <p class="text-red-600 font-medium">Please fix the following errors:</p>
                  <ul class="list-disc list-inside text-red-500 text-sm mt-2">
                    <li v-for="error in Object.values(locationForm.errors)" :key="error">{{ error }}</li>
                  </ul>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t">
                  <button 
                    type="submit" 
                    class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
                    :disabled="locationForm.processing"
                  >
                    {{ locationForm.processing ? 'Saving...' : (editingLocation ? 'Update Location' : 'Create Location') }}
                  </button>
                  <button 
                    type="button" 
                    @click="cancelLocationForm" 
                    class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
                    :disabled="locationForm.processing"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>

            <!-- Locations Table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-5 py-3">ID</th>
                    <th class="border border-gray-300 px-5 py-3">Name</th>
                    <th class="border border-gray-300 px-5 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="location in locations" :key="location.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-5 py-3">{{ location.id }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ location.name }}</td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex space-x-3">
                        <button 
                          @click="editLocation(location)" 
                          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm" 
                          :disabled="locationForm.processing"
                        >
                          Edit
                        </button>
                        <button 
                          @click="deleteLocation(location.id)" 
                          class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm" 
                          :disabled="locationForm.processing"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="locations.length === 0">
                    <td colspan="3" class="border border-gray-300 px-5 py-4 text-center text-gray-500">
                      No locations found. Click "Add New Location" to create one.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Roles Accordion -->
        <div class="border rounded-lg overflow-hidden">
          <button @click="open.roles = !open.roles" class="w-full bg-gray-100 px-10 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
            Roles <span>{{ open.roles ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.roles" class="p-6 bg-white border-t">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold">Manage Roles</h2>
              <button 
                @click="showRoleForm = true; editingRole = null; roleForm.reset()" 
                class="bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition-colors" 
                :disabled="roleForm.processing"
                v-if="!showRoleForm"
              >
                + Add New Role
              </button>
            </div>

            <!-- Role Create Form -->
            <div v-if="showRoleForm && !editingRole" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Create New Role</h3>
                <button 
                  @click="cancelRoleForm" 
                  class="text-gray-700 hover:underline"
                >
                  ← Back to Roles List
                </button>
              </div>

              <!-- Form -->
              <form @submit.prevent="saveRole" class="space-y-6">
                <!-- Role Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role Name
                  </label>
                  <input
                    v-model="roleForm.name"
                    type="text"
                    placeholder="Enter role name"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2
                          focus:ring-2 focus:ring-green-500 focus:border-green-500"
                  />
                  <p
                    v-if="roleForm.errors.name"
                    class="text-red-600 text-sm mt-1"
                  >
                    {{ roleForm.errors.name }}
                  </p>
                </div>

                <!-- Assign Permissions -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Assign Permissions
                    <span class="text-gray-500 text-sm">
                      ({{ props.permissions ? props.permissions.length : 0 }} available)
                    </span>
                  </label>
                  
                  <!-- Show message if no permissions -->
                  <div 
                    v-if="!props.permissions || props.permissions.length === 0" 
                    class="bg-yellow-50 border border-yellow-200 rounded p-4"
                  >
                    <p class="text-yellow-800 font-medium">No permissions available</p>
                    <p class="text-yellow-700 text-sm mt-1">
                      Please create some permissions first in the database.
                    </p>
                  </div>

                  <!-- Permissions checkboxes -->
                  <div v-else class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto">
                      <label
                        v-for="perm in props.permissions"
                        :key="perm.id"
                        class="flex items-center gap-3 bg-white px-3 py-2 rounded border cursor-pointer hover:bg-gray-50 transition"
                      >
                        <input
                          type="checkbox"
                          v-model="roleForm.permissions"
                          :value="perm.id"
                          class="accent-green-600 w-4 h-4"
                        />
                        <span class="text-gray-800 text-sm font-medium flex-1">{{ perm.name }}</span>
                      </label>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                      <p class="text-gray-600 text-sm">
                        Selected: <strong>{{ roleForm.permissions.length }}</strong> of {{ props.permissions.length }} permissions
                      </p>
                    </div>
                  </div>
                  
                  <p
                    v-if="roleForm.errors.permissions"
                    class="text-red-600 text-sm mt-1"
                  >
                    {{ roleForm.errors.permissions }}
                  </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                  <button
                    type="button"
                    @click="cancelRoleForm"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded
                          hover:bg-gray-400 transition"
                  >
                    Cancel
                  </button>

                  <button
                    type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded
                          hover:bg-green-700 transition"
                    :disabled="roleForm.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': roleForm.processing }"
                  >
                    {{ roleForm.processing ? 'Creating...' : 'Create Role' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- Role Edit Form -->
            <div v-if="showRoleForm && editingRole" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <h3 class="font-semibold mb-4 text-lg">Edit Role</h3>
              <form @submit.prevent="saveRole" class="space-y-5">
                <div>
                  <label class="block text-sm font-medium mb-2">Role Name *</label>
                  <input 
                    v-model="roleForm.name" 
                    type="text"
                    class="w-full border px-4 py-3 rounded" 
                    required 
                    :disabled="roleForm.processing"
                    :class="{ 'border-red-500': roleForm.errors.name }"
                    placeholder="Enter role name"
                  >
                  <p v-if="roleForm.errors.name" class="text-red-500 text-xs mt-1">{{ roleForm.errors.name }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium mb-2">Assign Permissions</label>
                  
                  <!-- Show message if no permissions -->
                  <div 
                    v-if="!props.permissions || props.permissions.length === 0" 
                    class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4"
                  >
                    <p class="text-yellow-800 text-sm">
                      No permissions available. Please create permissions first.
                    </p>
                  </div>

                  <!-- Permissions checkboxes -->
                  <div v-else class="border border-gray-200 rounded-lg p-4 bg-white max-h-60 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                      <label
                        v-for="perm in props.permissions"
                        :key="perm.id"
                        class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded border cursor-pointer hover:bg-gray-100 transition"
                      >
                        <input
                          type="checkbox"
                          v-model="roleForm.permissions"
                          :value="perm.id"
                          class="accent-blue-600 w-4 h-4"
                          :disabled="roleForm.processing"
                        />
                        <span class="text-gray-800 text-sm font-medium">{{ perm.name }}</span>
                      </label>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                      <p class="text-gray-600 text-sm">
                        Selected: <strong>{{ roleForm.permissions.length }}</strong> of {{ props.permissions.length }} permissions
                      </p>
                    </div>
                  </div>
                  <p v-if="roleForm.errors.permissions" class="text-red-500 text-xs mt-1">{{ roleForm.errors.permissions }}</p>
                </div>

                <!-- General form errors -->
                <div v-if="Object.keys(roleForm.errors).length > 0" class="bg-red-50 border border-red-200 rounded p-4">
                  <p class="text-red-600 font-medium">Please fix the following errors:</p>
                  <ul class="list-disc list-inside text-red-500 text-sm mt-2">
                    <li v-for="error in Object.values(roleForm.errors)" :key="error">{{ error }}</li>
                  </ul>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t">
                  <button 
                    type="submit" 
                    class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
                    :disabled="roleForm.processing"
                  >
                    {{ roleForm.processing ? 'Saving...' : 'Update Role' }}
                  </button>
                  <button 
                    type="button" 
                    @click="cancelRoleForm" 
                    class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
                    :disabled="roleForm.processing"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>

            <!-- Roles Table (Only show when not in form mode) -->
            <div v-if="!showRoleForm" class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-5 py-3">#</th>
                    <th class="border border-gray-300 px-5 py-3">Role Name</th>
                    <th class="border border-gray-300 px-5 py-3">Permissions</th>
                    <th class="border border-gray-300 px-5 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(role, index) in roles" :key="role.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-5 py-3">{{ index + 1 }}</td>
                    <td class="border border-gray-300 px-5 py-3 font-medium">{{ role.name }}</td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex flex-wrap gap-1">
                        <span 
                          v-for="perm in role.permissions" 
                          :key="perm.id" 
                          class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded"
                        >
                          {{ perm.name }}
                        </span>
                        <span v-if="!role.permissions || role.permissions.length === 0" class="text-gray-400 text-sm">
                          No permissions
                        </span>
                      </div>
                    </td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex space-x-2">
                        <button 
                          @click="editRole(role)" 
                          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm transition-colors" 
                          :disabled="roleForm.processing || isProtectedRole(role.name)"
                          :class="{ 'opacity-50 cursor-not-allowed': isProtectedRole(role.name) }"
                        >
                          {{ isProtectedRole(role.name) ? 'Protected' : 'Edit' }}
                        </button>
                        <button 
                          @click="deleteRole(role.id, role.name)" 
                          class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm transition-colors" 
                          :disabled="roleForm.processing || isProtectedRole(role.name)"
                          :class="{ 'opacity-50 cursor-not-allowed': isProtectedRole(role.name) }"
                        >
                          {{ isProtectedRole(role.name) ? 'Protected' : 'Delete' }}
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="roles.length === 0">
                    <td colspan="4" class="border border-gray-300 px-5 py-4 text-center text-gray-500">
                      No roles found. Click "Add New Role" to create one.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Users Accordion -->
        <div class="border rounded-lg overflow-hidden">
          <button @click="open.users = !open.users" class="w-full bg-gray-100 px-10 py-14 font-semibold flex justify-between hover:bg-gray-200 transition-colors">
            Users <span>{{ open.users ? "▲" : "▼" }}</span>
          </button>

          <div v-if="open.users" class="p-6 bg-white border-t">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold">Manage Users</h2>
              <button 
                @click="showUserForm = true; editingUser = null; userForm.reset()" 
                class="bg-blue-500 text-white px-5 py-3 rounded hover:bg-blue-600 transition-colors" 
                :disabled="userForm.processing"
                v-if="!showUserForm"
              >
                + Add New User
              </button>
            </div>

            <!-- User Inline Form -->
            <div v-if="showUserForm" class="bg-gray-50 p-6 rounded-lg mb-6 border">
              <h3 class="font-semibold mb-4 text-lg">{{ editingUser ? 'Edit User' : 'Create New User' }}</h3>
              <form @submit.prevent="saveUser" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-sm font-medium mb-2">Name *</label>
                    <input 
                      v-model="userForm.name" 
                      type="text"
                      class="w-full border px-4 py-3 rounded" 
                      required 
                      :disabled="userForm.processing"
                      :class="{ 'border-red-500': userForm.errors.name }"
                      placeholder="Enter user name"
                    >
                    <p v-if="userForm.errors.name" class="text-red-500 text-xs mt-1">{{ userForm.errors.name }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Email *</label>
                    <input 
                      v-model="userForm.email" 
                      type="email"
                      class="w-full border px-4 py-3 rounded" 
                      required 
                      :disabled="userForm.processing"
                      :class="{ 'border-red-500': userForm.errors.email }"
                      placeholder="Enter user email"
                    >
                    <p v-if="userForm.errors.email" class="text-red-500 text-xs mt-1">{{ userForm.errors.email }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">
                      {{ editingUser ? 'Password (leave empty to keep current)' : 'Password *' }}
                    </label>
                    <div class="flex">
                      <input
                        v-model="userForm.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full border px-4 py-3 rounded"
                        :required="!editingUser"
                        :disabled="userForm.processing"
                        :class="{ 'border-red-500': userForm.errors.password }"
                        :placeholder="editingUser ? 'Leave empty to keep current' : 'Enter password'"
                      />
                      <button
                        type="button"
                        @click="togglePassword"
                        class="ml-2 px-4 py-3 bg-gray-200 rounded hover:bg-gray-300"
                      >
                        {{ showPassword ? 'Hide' : 'Show' }}
                      </button>
                    </div>
                    <p v-if="userForm.errors.password" class="text-red-500 text-xs mt-1">{{ userForm.errors.password }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Assign Roles</label>
                    <div class="flex flex-wrap gap-4">
                      <label
                        v-for="role in roles"
                        :key="role.id"
                        class="flex items-center gap-2 bg-gray-100 px-4 py-3 rounded border"
                      >
                        <input
                          type="checkbox"
                          v-model="userForm.roles"
                          :value="role.id"
                          class="accent-blue-600"
                          :disabled="userForm.processing"
                        />
                        <span class="text-gray-800 text-sm">{{ role.name }}</span>
                      </label>
                    </div>
                    <p v-if="userForm.errors.roles" class="text-red-500 text-xs mt-1">{{ userForm.errors.roles }}</p>
                  </div>
                </div>

                <!-- General form errors -->
                <div v-if="userForm.hasErrors" class="bg-red-50 border border-red-200 rounded p-4">
                  <p class="text-red-600 font-medium">Please fix the following errors:</p>
                  <ul class="list-disc list-inside text-red-500 text-sm mt-2">
                    <li v-for="error in Object.values(userForm.errors)" :key="error">{{ error }}</li>
                  </ul>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t">
                  <button 
                    type="submit" 
                    class="bg-green-500 text-white px-5 py-3 rounded hover:bg-green-600 transition-colors" 
                    :disabled="userForm.processing"
                  >
                    {{ userForm.processing ? 'Saving...' : (editingUser ? 'Update User' : 'Create User') }}
                  </button>
                  <button 
                    type="button" 
                    @click="cancelUserForm" 
                    class="bg-gray-500 text-white px-5 py-3 rounded hover:bg-gray-600 transition-colors" 
                    :disabled="userForm.processing"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-5 py-3">#</th>
                    <th class="border border-gray-300 px-5 py-3">Name</th>
                    <th class="border border-gray-300 px-5 py-3">Email</th>
                    <th class="border border-gray-300 px-5 py-3">Password</th>
                    <th class="border border-gray-300 px-5 py-3">Roles</th>
                    <th class="border border-gray-300 px-5 py-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in users" :key="user.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-5 py-3">{{ index + 1 }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ user.name }}</td>
                    <td class="border border-gray-300 px-5 py-3">{{ user.email }}</td>
                    <td class="border border-gray-300 px-5 py-3 flex items-center">
                      <span>
                        {{ user.showPassword ? user.plain_password || '********' : '********' }}
                      </span>
                      <button
                        v-if="user.plain_password"
                        @click="toggleUserPassword(user)"
                        class="ml-2 px-3 py-2 bg-gray-200 rounded text-sm hover:bg-gray-300"
                      >
                        {{ user.showPassword ? 'Hide' : 'Show' }}
                      </button>
                    </td>
                    <td class="border border-gray-300 px-5 py-3">
                      <span 
                        v-for="role in user.roles" 
                        :key="role.id" 
                        class="inline-block bg-gray-200 text-gray-700 text-xs px-3 py-2 mr-2 mb-2 rounded"
                      >
                        {{ role.name }}
                      </span>
                    </td>
                    <td class="border border-gray-300 px-5 py-3">
                      <div class="flex space-x-3">
                        <button 
                          @click="editUser(user)" 
                          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm" 
                          :disabled="userForm.processing"
                        >
                          Edit
                        </button>
                        <button 
                          @click="deleteUser(user.id, user.name)" 
                          class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm" 
                          :disabled="userForm.processing"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="users.length === 0">
                    <td colspan="6" class="border border-gray-300 px-5 py-4 text-center text-gray-500">
                      No users found. Click "Add New User" to create one.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>