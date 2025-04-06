<template>
    <div class="card">
      <h3 class="card-title">{{ title }}</h3>
      <ul class="card-list">
        <li v-for="(item, index) in items" :key="index" class="card-item">
          <span>{{ item }}</span>
          <span class="arrow">›</span>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: String,
    items: Array
  })
  </script>
  
  <style scoped>
  .card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
  
  .card-title {
    margin-bottom: 16px;
    font-size: 18px;
    font-weight: 600;
  }
  
  .card-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .card-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    cursor: pointer;
    color: #333;
  }
  
  .card-item:hover {
    color: #000;
  }
  
  .arrow {
    font-weight: bold;
  }
  </style>
  