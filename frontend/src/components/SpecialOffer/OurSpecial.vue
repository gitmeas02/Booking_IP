<template>
  <div
    class="bg-gradient-to-br from-sky-50 to-blue-100 p-6 rounded-xl shadow-md h-[600px] min-w-80 max-w-96 flex flex-col"
  >
    <!-- Header Section -->
    <div class="flex items-center mb-6 flex-shrink-0">
      <h2 class="text-xl font-bold text-blue-900 pr-3 flex-1">
        Find and Book Your Perfect Stay
      </h2>
      <div class="bg-blue-200 p-2 rounded-full">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="w-6 h-6 text-blue-700"
        >
          <path
            fill-rule="evenodd"
            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
    </div>

    <!-- Features List -->
    <div class="space-y-3 mb-4 flex-1 overflow-y-auto">
      <!-- FREE CANCELLATION -->
      <div
        class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border-l-4 border-red-400 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
      >
        <div class="flex items-center gap-3">
          <div class="bg-red-100 p-3 rounded-full">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="currentColor"
              class="w-6 h-6 text-red-600"
            >
              <path
                fill-rule="evenodd"
                d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="font-bold uppercase text-gray-800 text-sm">
              FREE CANCELLATION
            </h3>
            <p class="text-xs text-gray-600 mt-1">
              Safe option, if plans go sideways
            </p>
            <p class="text-xs text-red-600 font-medium mt-1">
              Cancel up to 24 hours before
            </p>
          </div>
        </div>
      </div>

      <!-- PET FRIENDLY -->
      <div
        class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border-l-4 border-amber-400 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
      >
        <div class="flex items-center gap-3">
          <div class="bg-amber-100 p-3 rounded-full">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              width="24"
              height="24"
              stroke-width="2"
              class="text-amber-600"
            >
              <path
                d="M14.7 13.5c-1.1 -2 -1.441 -2.5 -2.7 -2.5c-1.259 0 -1.736 .755 -2.836 2.747c-.942 1.703 -2.846 1.845 -3.321 3.291c-.097 .265 -.145 .677 -.143 .962c0 1.176 .787 2 1.8 2c1.259 0 3 -1 4.5 -1s3.241 1 4.5 1c1.013 0 1.8 -.823 1.8 -2c0 -.285 -.049 -.697 -.146 -.962c-.475 -1.451 -2.512 -1.835 -3.454 -3.538z"
              ></path>
              <path
                d="M20.188 8.082a1.039 1.039 0 0 0 -.406 -.082h-.015c-.735 .012 -1.56 .75 -1.993 1.866c-.519 1.335 -.28 2.7 .538 3.052c.129 .055 .267 .082 .406 .082c.739 0 1.575 -.742 2.011 -1.866c.516 -1.335 .273 -2.7 -.54 -3.052z"
              ></path>
              <path
                d="M9.474 9c.055 0 .109 0 .163 -.011c.944 -.128 1.533 -1.346 1.32 -2.722c-.203 -1.297 -1.047 -2.267 -1.932 -2.267c-.055 0 -.109 0 -.163 .011c-.944 .128 -1.533 1.346 -1.32 2.722c.204 1.293 1.048 2.267 1.933 2.267z"
              ></path>
              <path
                d="M16.456 6.733c.214 -1.376 -.375 -2.594 -1.32 -2.722a1.164 1.164 0 0 0 -.162 -.011c-.885 0 -1.728 .97 -1.93 2.267c-.214 1.376 .375 2.594 1.32 2.722c.054 .007 .108 .011 .162 .011c.885 0 1.73 -.974 1.93 -2.267z"
              ></path>
              <path
                d="M5.69 12.918c.816 -.352 1.054 -1.719 .536 -3.052c-.436 -1.124 -1.271 -1.866 -2.009 -1.866c-.14 0 -.277 .027 -.407 .082c-.816 .352 -1.054 1.719 -.536 3.052c.436 1.124 1.271 1.866 2.009 1.866c.14 0 .277 -.027 .407 -.082z"
              ></path>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="font-bold uppercase text-gray-800 text-sm">
              PET FRIENDLY
            </h3>
            <p class="text-xs text-gray-600 mt-1">
              No worry about leaving your buddy home
            </p>
            <p class="text-xs text-amber-600 font-medium mt-1">
              Pet fee may apply
            </p>
          </div>
        </div>
      </div>

      <!-- EARN REWARD -->
      <div
        class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border-l-4 border-indigo-400 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
      >
        <div class="flex items-center gap-3">
          <div class="bg-indigo-100 p-3 rounded-full">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              width="24"
              height="24"
              stroke-width="2"
              class="text-indigo-600"
            >
              <path d="M12 9m-6 0a6 6 0 1 0 12 0a6 6 0 1 0 -12 0"></path>
              <path
                d="M12 15l3.4 5.89l1.598 -3.233l3.598 .232l-3.4 -5.889"
              ></path>
              <path
                d="M6.802 12l-3.4 5.89l3.598 -.233l1.598 3.232l3.4 -5.889"
              ></path>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="font-bold uppercase text-gray-800 text-sm">
              EARN REWARDS
            </h3>
            <p class="text-xs text-gray-600 mt-1">
              Earn points on every night you stay
            </p>
            <p class="text-xs text-indigo-600 font-medium mt-1">
              10 points per $1 spent
            </p>
          </div>
        </div>
      </div>

      <!-- SUPPORT SERVICE -->
      <div
        class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border-l-4 border-teal-400 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
      >
        <div class="flex items-center gap-3">
          <div class="bg-teal-100 p-3 rounded-full">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              width="24"
              height="24"
              stroke-width="2"
              class="text-teal-600"
            >
              <path d="M4 14v-3a8 8 0 1 1 16 0v3"></path>
              <path d="M18 19c0 1.657 -2.686 3 -6 3"></path>
              <path
                d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"
              ></path>
              <path
                d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"
              ></path>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="font-bold text-gray-800 text-sm">24/7 SUPPORT</h3>
            <p class="text-xs text-gray-600 mt-1">
              Got any problem? Contact our agents
            </p>
            <p class="text-xs text-teal-600 font-medium mt-1">
              Available round the clock
            </p>
          </div>
        </div>
      </div>

      <!-- BEST PRICE GUARANTEE -->
      <div
        class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border-l-4 border-green-400 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
      >
        <div class="flex items-center gap-3">
          <div class="bg-green-100 p-3 rounded-full">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              width="24"
              height="24"
              stroke-width="2"
              class="text-green-600"
            >
              <path d="M9 12l2 2l4 -4"></path>
              <path
                d="M21 12c-1 0 -3 -1 -3 -3s2 -3 3 -3s3 1 3 3s-2 3 -3 3"
              ></path>
              <path
                d="M3 12c1 0 3 -1 3 -3s-2 -3 -3 -3s-3 1 -3 3s2 3 3 3"
              ></path>
              <path
                d="M12 3c0 1 -1 3 -3 3s-3 -2 -3 -3s1 -3 3 -3s3 2 3 3"
              ></path>
              <path
                d="M12 21c0 -1 -1 -3 -3 -3s-3 2 -3 3s1 3 3 3s3 -2 3 -3"
              ></path>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="font-bold uppercase text-gray-800 text-sm">
              BEST PRICE GUARANTEE
            </h3>
            <p class="text-xs text-gray-600 mt-1">
              We'll match any lower price you find
            </p>
            <p class="text-xs text-green-600 font-medium mt-1">
              Terms & conditions apply
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Call to Action Section -->
    <div
      class="bg-white/90 backdrop-blur-sm rounded-lg p-4 border border-blue-200 flex-shrink-0"
    >
      <div class="text-center">
        <h4 class="font-bold text-gray-800 mb-2">Ready to Book?</h4>
        <p class="text-xs text-gray-600 mb-3">
          Start exploring amazing deals and find your perfect stay today!
        </p>
        <button
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 w-full"
        >
          Browse Hotels
        </button>
      </div>
    </div>

    <!-- Trust Indicators -->
    <div
      class="mt-4 flex justify-center space-x-2 text-xs text-gray-500 flex-shrink-0"
    >
      <span class="flex items-center">
        <svg
          class="w-3 h-3 mr-1 text-green-500"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
            clip-rule="evenodd"
          ></path>
        </svg>
        Verified
      </span>
      <span class="flex items-center">
        <svg
          class="w-3 h-3 mr-1 text-yellow-500"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
          ></path>
        </svg>
        Trusted
      </span>
      <span class="flex items-center">
        <svg
          class="w-3 h-3 mr-1 text-blue-500"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            fill-rule="evenodd"
            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
            clip-rule="evenodd"
          ></path>
        </svg>
        Secure
      </span>
    </div>
  </div>
</template>

<style scoped></style>

<script setup></script>
