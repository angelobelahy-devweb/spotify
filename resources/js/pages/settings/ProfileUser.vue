<script setup>

</script>

<template>

  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md overflow-hidden">

      <!-- Header -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-32"></div>

      <!-- Profile Content -->
      <div class="relative px-6 pb-6">

        <!-- Avatar -->
        <div class="flex justify-center">
          <img
            class="w-28 h-28 rounded-full border-4 border-white shadow-lg -mt-14 object-cover"
            src="https://i.pravatar.cc/300"
            alt="User Avatar"
          />
        </div>

        <!-- User Info -->
        <div class="text-center mt-4">
          <h2 class="text-2xl font-bold text-gray-800">
            Jean Dupont
          </h2>

          <p class="text-indigo-500 font-medium">
            Développeur Frontend
          </p>

          <p class="text-gray-500 mt-3 text-sm leading-relaxed">
            Passionné par Vue.js, Tailwind CSS et les interfaces modernes.
          </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 text-center mt-6">

          <div class="bg-gray-50 p-3 rounded-xl">
            <p class="text-xl font-bold text-gray-800">245</p>
            <p class="text-gray-500 text-sm">Posts</p>
          </div>

          <div class="bg-gray-50 p-3 rounded-xl">
            <p class="text-xl font-bold text-gray-800">12K</p>
            <p class="text-gray-500 text-sm">Followers</p>
          </div>

          <div class="bg-gray-50 p-3 rounded-xl">
            <p class="text-xl font-bold text-gray-800">530</p>
            <p class="text-gray-500 text-sm">Following</p>
          </div>

        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6">

          <button
            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl font-medium transition"
          >
            Suivre
          </button>

          <button
            class="flex-1 border border-gray-300 hover:bg-gray-100 text-gray-700 py-2 rounded-xl font-medium transition"
          >
            Message
          </button>

        </div>

      </div>
    </div>
  </div>
</template>

