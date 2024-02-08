<template>
  <app-layout :title="__('video_history')">
    <div class="w-full mx-auto rounded bg-gray-50 xl:max-w-7xl">
      <div class="p-4 text-xl font-bold">
        ប្រវត្តិ​វីដេអូ
      </div>
      <div class="px-2 py-2 bg-gray-100">
        <div class="flex gap-1 sm:gap-6">
          <button class="inline-flex items-center justify-center flex-1 px-4 py-2 space-x-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>TV1</span>
          </button>
          <button class="inline-flex items-center justify-center flex-1 px-4 py-2 space-x-2 text-sm font-medium text-gray-500 bg-white border border-gray-500 rounded-md shadow-sm hover:bg-red-700 hover:text-white hover:border-transparent focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>TV2</span>
          </button>
          <button class="inline-flex items-center justify-center flex-1 px-4 py-2 space-x-2 text-sm font-medium text-gray-500 bg-white border border-gray-500 rounded-md shadow-sm hover:bg-red-700 hover:text-white hover:border-transparent focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>TV3</span>
          </button>
        </div>
      </div>
      <div class="grid gap-3 p-4 sm:gap-6 sm:grid-cols-2">
        <div class="flex items-center gap-3">
          <div class="relative h-24 overflow-hidden bg-black rounded w-44">
            <div class="absolute inset-0">
              <img class="w-full h-full" src="https://images.unsplash.com/photo-1582792247472-c92c79ff2561?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1635&q=80" alt="">
            </div>
            <div class="absolute inset-0 flex items-center justify-center w-full h-full bg-black/50">
              <button @click="openModal" type="button" class="inline-flex items-center text-white border border-transparent rounded-full shadow-sm hover:text-red-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-start justify-between flex-1 h-full">
            <div class="text-lg font-bold">
              លេខ​ភ្នាល់ # 16
            </div>
            <div class="text-lg font-bold uppercase">
             <span> អ្នកឈ្នះ</span> <span class="text-wala">wala</span>
            </div>
            <div>
              <small>24/06/2022 01:56:16 AM</small>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative h-24 overflow-hidden bg-black rounded w-44">
            <div class="absolute inset-0">
              <img class="w-full h-full" src="https://images.unsplash.com/photo-1582792247472-c92c79ff2561?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1635&q=80" alt="">
            </div>
            <div class="absolute inset-0 flex items-center justify-center w-full h-full bg-black/50">
              <button type="button" class="inline-flex items-center text-white border border-transparent rounded-full shadow-sm hover:text-red-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-start justify-between flex-1 h-full">
            <div class="text-lg font-bold">
              លេខ​ភ្នាល់ # 16
            </div>
            <div class="text-lg font-bold uppercase">
             <span> អ្នកឈ្នះ</span> <span class="text-meron">meron</span>
            </div>
            <div>
              <small>24/06/2022 01:56:16 AM</small>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative h-24 overflow-hidden bg-black rounded w-44">
            <div class="absolute inset-0">
              <img class="w-full h-full" src="https://images.unsplash.com/photo-1582792247472-c92c79ff2561?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1635&q=80" alt="">
            </div>
            <div class="absolute inset-0 flex items-center justify-center w-full h-full bg-black/50">
              <button type="button" class="inline-flex items-center text-white border border-transparent rounded-full shadow-sm hover:text-red-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-start justify-between flex-1 h-full">
            <div class="text-lg font-bold">
              លេខ​ភ្នាល់ # 16
            </div>
            <div class="text-lg font-bold uppercase">
             <span> អ្នកឈ្នះ</span> <span class="text-draw">ស្មើរ</span>
            </div>
            <div>
              <small>24/06/2022 01:56:16 AM</small>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative h-24 overflow-hidden bg-black rounded w-44">
            <div class="absolute inset-0">
              <img class="w-full h-full" src="https://images.unsplash.com/photo-1582792247472-c92c79ff2561?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1635&q=80" alt="">
            </div>
            <div class="absolute inset-0 flex items-center justify-center w-full h-full bg-black/50">
              <button type="button" class="inline-flex items-center text-white border border-transparent rounded-full shadow-sm hover:text-red-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-start justify-between flex-1 h-full">
            <div class="text-lg font-bold">
              លេខ​ភ្នាល់ # 16
            </div>
            <div class="text-lg font-bold uppercase">
             <span> អ្នកឈ្នះ</span> <span class="text-cancel">បោះបង់</span>
            </div>
            <div>
              <small>24/06/2022 01:56:16 AM</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/90" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-full p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="relative w-full text-left align-middle transition-all transform bg-white shadow-xl xl:max-w-5xl rounded-2xl"
            >
            <button @click="closeModal" type="button" class="absolute inline-flex items-center p-2 text-white bg-black rounded-full shadow-sm ring-0 focus:outline-none -top-5 -right-5">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
              <iframe class="w-full aspect-[16/9] rounded overflow-hidden" src="http://updatekh.com:5080/sabong/play.html?name=ph2" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;"></iframe>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  </app-layout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';
  const isOpen = ref(false)

  function closeModal() {
    isOpen.value = false
  }
  function openModal() {
    isOpen.value = true
  }
</script>