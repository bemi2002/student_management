<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2
          class="font-bold text-3xl md:text-4xl text-gradient bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600"
        >
          Enrollments Management
        </h2>
        <div class="flex items-center space-x-2">
          <span
            class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full font-semibold text-base"
          >
            {{ filteredEnrollments.length }} records
          </span>
        </div>
      </div>
    </template>

    <div class="p-6">
      <!-- Header -->
      <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6"
      >
        <Link
          :href="route('enrollmentss.create')"
          class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5"
        >
          <svg
            class="w-6 h-6 mr-3 transition-transform group-hover:rotate-90"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 4v16m8-8H4"
            />
          </svg>
          Create New Enrollment
        </Link>

        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <svg
              class="h-6 w-6 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </div>

          <input
            v-model="search"
            type="text"
            placeholder=""
            class="pl-12 pr-6 py-4 w-full md:w-96 border-2 border-gray-200 focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 rounded-xl bg-white shadow-sm transition-all outline-none text-base"
          />
        </div>
      </div>

      <!-- Table Container -->
      <div
        class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200 overflow-hidden"
      >
        <!-- Header -->
        <div
          class="px-8 py-5 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200"
        >
          <div class="flex items-center">
            <div class="p-3 bg-white rounded-xl shadow-sm mr-4">
              <svg
                class="w-7 h-7 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Enrollment Records</h3>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50">
                <th
                  class="py-5 px-8 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-200"
                >
                  <div class="flex items-center">
                    <span>ID</span>
                  </div>
                </th>

                <th class="th">Title</th>
                <th class="th">Description</th>
                <th class="th">Course</th>
                <th class="th">Course Type</th>
                <th class="th">Company Address</th>
                <th class="th">Organization</th>
                <th class="th">Capacity</th>
                <th class="th">Amount</th>
                <th class="th">Status</th>
                <th class="th">Telegram</th>
                <th class="th">Enrollment Date</th>
                <th class="th">Actions</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
              <tr
                v-for="e in filteredEnrollments"
                :key="e.id"
                class="hover:bg-indigo-50/30 transition-colors duration-200 group"
              >
                <td class="td">
                  <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 font-bold text-base"
                  >
                    {{ e.id }}
                  </span>
                </td>

                <td class="td font-medium">{{ e.title || "-" }}</td>

                <td class="td">
                  <div class="text-base text-gray-600 max-w-xs truncate">
                    {{ e.description || "-" }}
                  </div>
                </td>

                <td class="td">
                  <span
                    class="badge"
                    :class="e.course?.course_name ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ e.course?.course_name || "N/A" }}
                  </span>
                </td>

                <td class="td">
                  <span
                    class="badge"
                    :class="e.course_type?.course_type ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ e.course_type?.course_type || "N/A" }}
                  </span>
                </td>

                <td class="td">
                  <span
                    class="badge"
                    :class="e.company_address?.city ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ e.company_address?.city || "N/A" }}
                  </span>
                </td>

                <td class="td font-medium">{{ e.organization || "-" }}</td>

                <td class="td">
                  <span class="font-bold text-gray-800">
                    {{ e.student_capacity ?? 0 }}
                  </span>
                </td>

                <td class="td">
                  <span
                    class="px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 font-bold rounded-lg text-base"
                  >
                    {{ e.amount_to_be_paid ?? 0 }}
                  </span>
                </td>

                <td class="td">
                  <span class="badge" :class="getStatusClass(e.completion_status)">
                    {{ e.completion_status || "Not Started" }}
                  </span>
                </td>

                <td class="td">
                  <a
                    v-if="e.telegram_link"
                    :href="e.telegram_link"
                    target="_blank"
                    class="text-blue-600 hover:underline font-medium"
                  >
                    Join
                  </a>
                  <span v-else class="text-gray-400">-</span>
                </td>

                <td class="td font-medium">
                  {{ formatDate(e.enrollment_date) }}
                </td>

                <td class="td">
                  <div class="flex items-center space-x-4">
                    <Link
                      :href="route('enrollmentss.show', e.id)"
                      class="action-btn bg-blue-50 text-blue-700 hover:bg-blue-100 text-lg"
                      title="View"
                    >
                      👁
                    </Link>

                    <Link
                      :href="route('enrollmentss.edit', e.id)"
                      class="action-btn bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-lg"
                      title="Edit"
                    >
                      ✏️
                    </Link>

                    <button
                      @click="confirmDelete(e.id)"
                      class="action-btn bg-red-50 text-red-700 hover:bg-red-100 text-lg"
                      title="Delete"
                    >
                      🗑
                    </button>
                  </div>
                </td>
              </tr>

              <!-- No Data -->
              <tr v-if="filteredEnrollments.length === 0">
                <td colspan="13" class="py-16 text-center">
                  <div class="flex flex-col items-center">
                    <svg
                      class="w-16 h-16 text-gray-300 mb-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                      />
                    </svg>
                    <p class="text-xl text-gray-600 font-medium">No enrollments found</p>
                    <p class="text-gray-500 mt-2">Try adjusting your search criteria</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Delete Modal -->
      <div
        v-if="showDelete"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
      >
        <div
          class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200"
        >
          <div class="flex items-center mb-4">
            <div class="p-3 bg-red-100 rounded-xl mr-4">
              <svg
                class="w-8 h-8 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Confirm Deletion</h3>
          </div>
          <p class="text-lg text-gray-600 mb-8">
            Are you sure you want to delete this enrollment? This action cannot be undone.
          </p>

          <div class="flex justify-end space-x-4">
            <button class="btn-secondary text-base" @click="showDelete = false">
              Cancel
            </button>

            <button class="btn-danger text-base" @click="deleteEnrollment">
              Delete Enrollment
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  enrollments: { type: Array, default: () => [] },
});

const search = ref("");
const showDelete = ref(false);
const deleteId = ref(null);

const localEnrollments = ref([...props.enrollments]);

const filteredEnrollments = computed(() => {
  return localEnrollments.value.filter((e) =>
    e.title?.toLowerCase().includes(search.value.toLowerCase())
  );
});

function confirmDelete(id) {
  deleteId.value = id;
  showDelete.value = true;
}

function deleteEnrollment() {
  if (!deleteId.value) return;

  router.delete(route("enrollmentss.destroy", deleteId.value), {
    onSuccess: () => {
      localEnrollments.value = localEnrollments.value.filter(
        (e) => e.id !== deleteId.value
      );
      showDelete.value = false;
      deleteId.value = null;
    },
  });
}

function formatDate(date) {
  if (!date) return "-";
  return date.split("T")[0];
}

function getStatusClass(status) {
  const map = {
    completed: "bg-green-100 text-green-800",
    in_progress: "bg-blue-100 text-blue-800",
    pending: "bg-yellow-100 text-yellow-800",
    cancelled: "bg-red-100 text-red-800",
    not_started: "bg-gray-100 text-gray-800",
  };
  return map[status?.toLowerCase()] ?? "bg-gray-100 text-gray-800";
}
</script>

<style scoped>
.th {
  @apply py-5 px-8 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border-b border-gray-200;
}
.td {
  @apply py-5 px-8 whitespace-nowrap text-gray-800 text-base;
}
.badge {
  @apply px-4 py-2 text-sm font-semibold rounded-full;
}
.action-btn {
  @apply p-3 rounded-xl transition-all duration-200 hover:scale-110;
}
.btn-danger {
  @apply px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 hover:shadow-lg transition-all;
}
.btn-secondary {
  @apply px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 hover:border-gray-400 transition-all;
}
.text-gradient {
  -webkit-background-clip: text;
  background-clip: text;
}
</style>