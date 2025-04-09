<template>
    <footer class="bg-[#2A2325] text-white px-8 py-12">
      <div class="grid md:grid-cols-4 gap-8">
        <div>
          <img src="../assets/logo.png" class="h-10 mb-2" />
          <p>Feel free to reach out if you want collaborate with us, or simply chat</p>
          <div class="mt-2 flex bg-gray-500 rounded px-2 items-center">
            <input placeholder="Email Address" class="bg-transparent flex-1 p-2 text-white" />
            <Send />
          </div>
          <div>
            <h4 class="mt-4">Follow Us</h4>
          </div>
          <div class="flex mt-4 space-x-6">
            <Facebook/>
            <Instagram/>
            <Linkedin/>
            <MessageCircleMore/>
            <!-- <SocialIcon icon="facebook" />
            <SocialIcon icon="instagram" />
            <SocialIcon icon="linkedin" />
            <SocialIcon icon="wechat" /> -->
          </div>
        </div>
  
        <div>
          <h4 class="font-bold mb-2">Services</h4>
          <ul class="space-y-1 text-sm">
            <li>Store Directory</li>
            <li>Top Hotels</li>
            <li>Quick Links</li>
            <li>Important Links</li>
            <li>Insights</li>
            <li>Knowledge Center</li>
          </ul>
        </div>
  
        <div>
          <h4 class="font-bold mb-2">Company</h4>
          <ul class="space-y-1 text-sm">
            <li>Home</li>
            <li>About Us</li>
            <li>Services</li>
            <li>Career</li>
            <li>Contact</li>
          </ul>
        </div>
  
        <div>
          <h4 class="font-bold mb-2">Phnom Penh</h4>
          <p class="text-sm">#123, Sangkat ... Khan ... Phnom Penh<br />Cambodia</p>
          <p class="mt-2 text-sm">+855 123142341</p>
          <p class="text-sm">info@gmail.com</p>
        </div>
      </div>
    </footer>
  </template>

<script setup>
import {Send } from 'lucide-vue-next';
import { Facebook } from 'lucide-vue-next';
import { Instagram } from 'lucide-vue-next';
import { Linkedin } from 'lucide-vue-next';
import { MessageCircleMore } from 'lucide-vue-next';

</script>
