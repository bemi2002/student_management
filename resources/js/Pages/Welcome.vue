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
      <nav class="flex justify-between items-center px-12 py-3">
        <div class="flex items-center space-x-3">
          <img 
            src="https://tse2.mm.bing.net/th/id/OIP.KjNeiFwKrH5nCPb_MtehQAHaEK?cb=ucfimgc2&rs=1&pid=ImgDetMain&o=7&rm=3" 
            alt="logo" 
            class="w-16 h-16 object-contain drop-shadow-md" 
          />
          <h1 class="font-bold text-sky-700 text-2xl">Student Management System</h1>
        </div>

        <div class="hidden md:flex space-x-8 text-gray-600 font-medium">
          <Link href="#" class="hover:text-sky-600 transition">Home</Link>
          <Link href="#" class="hover:text-sky-600 transition">About</Link>
          <Link href="#" class="hover:text-sky-600 transition">Contact</Link>
          </div>

        <div class="flex space-x-3">
          <Link 
            v-if="canLogin" 
            :href="route('login')" 
            class="px-4 py-2 border border-sky-500 text-sky-600 rounded-md hover:bg-sky-50 font-semibold transition">
            Login
          </Link>
          <Link 
            v-if="canRegister" 
            :href="route('register')" 
            class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 font-semibold transition">
            Register
          </Link>
        </div>
      </nav>
    </header>

    <!-- Hero Carousel -->
    <section class="relative w-full h-[77vh] overflow-hidden flex justify-center items-center mt-[72px]">
      <div
        v-for="(image, index) in images"
        :key="index"
        class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
        :class="{ 'opacity-100 z-20': current === index, 'opacity-0 z-10': current !== index }"
      >
        <img
          :src="image"
          alt="carousel image"
          class="w-full h-full object-cover object-center"
        />
      </div>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/60"></div>

      <!-- Hero Text -->
      <div class="relative z-30 text-white text-center px-4">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">
          Learn With Us.<br />Improve With Us.
        </h1>
        <Link :href="route('login')" class="inline-block bg-yellow-400 text-gray-900 font-semibold px-8 py-3 rounded-md shadow-lg hover:bg-yellow-500 transition">
          START NOW →
        </Link>
      </div>

      <!-- Floating Info Boxes -->
      <div class="absolute bottom-[-4rem] w-[90%] max-w-5xl grid grid-cols-1 sm:grid-cols-3 gap-6 z-30">
        <div class="bg-gray-900/90 text-white rounded-md p-6 text-left hover:bg-gray-900 transition">
          <div class="text-yellow-400 text-3xl mb-3">🚀</div>
          <h3 class="font-semibold text-lg mb-2">Streamline Success, Simplify Education.</h3>
          <p class="text-gray-300 text-sm">Transforming management into effortless achievement.</p>
        </div>
        <div class="bg-gray-900/90 text-white rounded-md p-6 text-left hover:bg-gray-900 transition">
          <div class="text-yellow-400 text-3xl mb-3">🎯</div>
          <h3 class="font-semibold text-lg mb-2">Where Data Meets Destiny – Shaping Future Leaders.</h3>
          <p class="text-gray-300 text-sm">Harnessing insights to guide academic excellence.</p>
        </div>
        <div class="bg-gray-900/90 text-white rounded-md p-6 text-left hover:bg-gray-900 transition">
          <div class="text-yellow-400 text-3xl mb-3">💡</div>
          <h3 class="font-semibold text-lg mb-2">Empowering Education, One Student at a Time.</h3>
          <p class="text-gray-300 text-sm">Driving innovation, impact, and inclusion in learning.</p>
        </div>
      </div>
    </section>

    <!-- Offer Section -->
    <section class="mt-36 py-20 text-center bg-gray-50">
      <h2 class="text-3xl font-bold text-sky-700 mb-10">What We Offer</h2>
      <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">

        <!-- Admin -->
        <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition">
          <div class="text-4xl mb-4 text-sky-500">🗂️</div>
          <h3 class="font-semibold text-lg mb-2">Efficient Administration</h3>
          <p class="text-gray-600 text-sm">
            Streamlined management tools for admins to oversee operations, monitor performance, and ensure smooth academic workflows.
          </p>
        </div>

        <!-- Teacher -->
        <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition">
          <div class="text-4xl mb-4 text-sky-500">📚</div>
          <h3 class="font-semibold text-lg mb-2">Empowered Teaching</h3>
          <p class="text-gray-600 text-sm">
            Tools and resources for teachers to manage courses, track student progress, and deliver high-quality education efficiently.
          </p>
        </div>

        <!-- Student -->
        <div class="p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition">
          <div class="text-4xl mb-4 text-sky-500">🎓</div>
          <h3 class="font-semibold text-lg mb-2">Enhanced Learning</h3>
          <p class="text-gray-600 text-sm">
            Personalized dashboards and learning tools for students to track their achievements, access course materials, and succeed academically.
          </p>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-sky-700 text-white py-12">
      <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- Address -->
        <div>
          <h3 class="font-semibold text-lg mb-3">Address</h3>
          <p class="text-sm text-gray-200">
            Addis Ababa, Ethiopia <br />
            Megenegna, Metababer Building, 7th Floor <br />
            Office Number: 711A
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="font-semibold text-lg mb-3">Quick Links</h3>
          <ul class="space-y-2 text-gray-200 text-sm">
            <li><Link href="#" class="hover:text-yellow-400 transition">Home</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">About Us</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Projects</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Blogs</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Contact</Link></li>
          </ul>
        </div>

        <!-- Important Links -->
        <div>
          <h3 class="font-semibold text-lg mb-3">Important Links</h3>
          <ul class="space-y-2 text-gray-200 text-sm">
            <li><Link href="#" class="hover:text-yellow-400 transition">Achievements</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Programs</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Vacancies</Link></li>
            <li><Link href="#" class="hover:text-yellow-400 transition">Partners</Link></li>
          </ul>
        </div>

        <!-- Let’s Connect -->
        <div>
          <h3 class="font-semibold text-lg mb-3">Let’s Connect!</h3>
          <p class="text-gray-200 text-sm">
            Connect with entrepreneurs, build your network, make great business.
          </p>
        </div>
      </div>

      <!-- Copyright -->
      <div class="mt-8 border-t border-sky-600 pt-4 text-center text-gray-300 text-sm">
        © {{ new Date().getFullYear() }} Student Management System. All rights reserved.
      </div>
    </footer>
  </div>
</template>
