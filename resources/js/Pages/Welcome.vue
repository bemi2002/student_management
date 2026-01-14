<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
})

// Carousel images (with new order)
const images = [
  'https://i.pinimg.com/1200x/e7/00/21/e70021473a6dae39ad676d8e07d642e2.jpg',
  'https://i.pinimg.com/1200x/d5/ab/bb/d5abbbb66a0e729aef4025fa61af34e7.jpg',
  'https://i.pinimg.com/736x/5a/72/a1/5a72a1c0db3d4a6e84cf6ca8a5d80bd5.jpg'
]

const current = ref(0)
let interval = null

onMounted(() => {
  interval = setInterval(() => {
    current.value = (current.value + 1) % images.length
  }, 5000)
})

onUnmounted(() => clearInterval(interval))
</script>

<template>
  <Head title="Welcome | Student Management System" />

  <div class="flex flex-col min-h-screen font-sans bg-white text-gray-900">

    <!-- Navbar -->
    <header class="fixed top-0 w-full bg-white shadow-sm z-50">
      <nav class="flex flex-col sm:flex-row justify-between items-center px-4 sm:px-6 md:px-8 lg:px-12 py-3 gap-3 sm:gap-0">
        <!-- Logo & Title -->
        <div class="flex items-center space-x-2 sm:space-x-3 w-full sm:w-auto justify-between sm:justify-start">
          <div class="flex items-center space-x-2 sm:space-x-3">
            <img 
              src="https://tse2.mm.bing.net/th/id/OIP.KjNeiFwKrH5nCPb_MtehQAHaEK?cb=ucfimgc2&rs=1&pid=ImgDetMain&o=7&rm=3" 
              alt="logo" 
              class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 object-contain drop-shadow-md" 
            />
            <h1 class="font-bold text-sky-700 text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl truncate">
              Student Management System
            </h1>
          </div>
          
          <!-- Mobile Menu Button (Optional) -->
          <button class="sm:hidden text-sky-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden sm:flex space-x-4 md:space-x-6 lg:space-x-8 text-gray-600 font-medium">
          <Link href="#" class="hover:text-sky-600 transition text-sm md:text-base">Home</Link>
          <Link href="#" class="hover:text-sky-600 transition text-sm md:text-base">About</Link>
          <Link href="#" class="hover:text-sky-600 transition text-sm md:text-base">Contact</Link>
        </div>

        <!-- Auth Buttons -->
        <div class="flex space-x-2 sm:space-x-3 w-full sm:w-auto justify-center sm:justify-end">
          <Link 
            v-if="canLogin" 
            :href="route('login')" 
            class="px-3 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2 border border-sky-500 text-sky-600 rounded-md hover:bg-sky-50 font-semibold transition text-xs sm:text-sm md:text-base whitespace-nowrap flex-1 sm:flex-none text-center">
            Login
          </Link>
          <Link 
            v-if="canRegister" 
            :href="route('register')" 
            class="px-3 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 font-semibold transition text-xs sm:text-sm md:text-base whitespace-nowrap flex-1 sm:flex-none text-center">
            Register
          </Link>
        </div>
      </nav>
    </header>

    <!-- Hero Carousel -->
    <section class="relative w-full min-h-[50vh] sm:h-[60vh] md:h-[70vh] lg:h-[77vh] overflow-hidden flex justify-center items-center mt-16 sm:mt-20 md:mt-24">
      <div
        v-for="(image, index) in images"
        :key="index"
        class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
        :class="{ 'opacity-100 z-20': current === index, 'opacity-0 z-10': current !== index }"
      >
        <img
          :src="image"
          :alt="`Hero image ${index + 1}`"
          class="w-full h-full object-cover object-center"
        />
      </div>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/50 md:bg-black/60"></div>

      <!-- Hero Text -->
      <div class="relative z-30 text-white text-center px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-4 sm:mb-6 leading-tight drop-shadow-lg">
          Learn With Us.<br class="hidden sm:block" />Improve With Us.
        </h1>
        <Link :href="route('login')" class="inline-block bg-yellow-400 text-gray-900 font-semibold px-6 py-2.5 sm:px-8 sm:py-3 md:px-10 md:py-3.5 rounded-md shadow-lg hover:bg-yellow-500 transition text-sm sm:text-base md:text-lg">
          START NOW →
        </Link>
      </div>

      <!-- Floating Info Boxes -->
      <div class="absolute -bottom-10 sm:-bottom-12 md:-bottom-16 lg:-bottom-20 xl:-bottom-24 left-1/2 transform -translate-x-1/2 w-[90%] max-w-5xl px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 z-30">
          <div class="bg-gray-900/90 text-white rounded-md p-4 sm:p-5 md:p-6 text-left hover:bg-gray-900 transition transform hover:-translate-y-1 duration-300">
            <div class="text-yellow-400 text-2xl sm:text-3xl mb-2 sm:mb-3">🚀</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-1 sm:mb-2">Streamline Success, Simplify Education.</h3>
            <p class="text-gray-300 text-xs sm:text-sm">Transforming management into effortless achievement.</p>
          </div>
          <div class="bg-gray-900/90 text-white rounded-md p-4 sm:p-5 md:p-6 text-left hover:bg-gray-900 transition transform hover:-translate-y-1 duration-300">
            <div class="text-yellow-400 text-2xl sm:text-3xl mb-2 sm:mb-3">🎯</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-1 sm:mb-2">Where Data Meets Destiny – Shaping Future Leaders.</h3>
            <p class="text-gray-300 text-xs sm:text-sm">Harnessing insights to guide academic excellence.</p>
          </div>
          <div class="bg-gray-900/90 text-white rounded-md p-4 sm:p-5 md:p-6 text-left hover:bg-gray-900 transition transform hover:-translate-y-1 duration-300">
            <div class="text-yellow-400 text-2xl sm:text-3xl mb-2 sm:mb-3">💡</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-1 sm:mb-2">Empowering Education, One Student at a Time.</h3>
            <p class="text-gray-300 text-xs sm:text-sm">Driving innovation, impact, and inclusion in learning.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Offer Section -->
    <section class="mt-24 sm:mt-28 md:mt-32 lg:mt-36 py-12 sm:py-16 md:py-20 text-center bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-sky-700 mb-8 sm:mb-10 md:mb-12">What We Offer</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

          <!-- Admin -->
          <div class="p-4 sm:p-5 md:p-6 lg:p-8 bg-white rounded-xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 duration-300">
            <div class="text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4 text-sky-500">🗂️</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Efficient Administration</h3>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base">
              Streamlined management tools for admins to oversee operations, monitor performance, and ensure smooth academic workflows.
            </p>
          </div>

          <!-- Teacher -->
          <div class="p-4 sm:p-5 md:p-6 lg:p-8 bg-white rounded-xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 duration-300">
            <div class="text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4 text-sky-500">📚</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Empowered Teaching</h3>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base">
              Tools and resources for teachers to manage courses, track student progress, and deliver high-quality education efficiently.
            </p>
          </div>

          <!-- Student -->
          <div class="p-4 sm:p-5 md:p-6 lg:p-8 bg-white rounded-xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 duration-300">
            <div class="text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4 text-sky-500">🎓</div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Enhanced Learning</h3>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base">
              Personalized dashboards and learning tools for students to track their achievements, access course materials, and succeed academically.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-sky-700 text-white py-8 sm:py-10 md:py-12 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
          
          <!-- Address -->
          <div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Address</h3>
            <p class="text-xs sm:text-sm text-gray-200">
              Addis Ababa, Ethiopia <br />
              Megenegna, Metababer Building, 7th Floor <br />
              Office Number: 711A
            </p>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Quick Links</h3>
            <ul class="space-y-1 sm:space-y-2 text-gray-200 text-xs sm:text-sm">
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Home</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">About Us</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Projects</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Blogs</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Contact</Link></li>
            </ul>
          </div>

          <!-- Important Links -->
          <div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Important Links</h3>
            <ul class="space-y-1 sm:space-y-2 text-gray-200 text-xs sm:text-sm">
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Achievements</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Programs</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Vacancies</Link></li>
              <li><Link href="#" class="hover:text-yellow-400 transition inline-block">Partners</Link></li>
            </ul>
          </div>

          <!-- Let's Connect -->
          <div>
            <h3 class="font-semibold text-base sm:text-lg md:text-xl mb-2 sm:mb-3">Let's Connect!</h3>
            <p class="text-gray-200 text-xs sm:text-sm">
              Connect with entrepreneurs, build your network, make great business.
            </p>
          </div>
        </div>

        <!-- Copyright -->
        <div class="mt-6 sm:mt-8 border-t border-sky-600 pt-4 text-center text-gray-300 text-xs sm:text-sm">
          © {{ new Date().getFullYear() }} Student Management System. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>