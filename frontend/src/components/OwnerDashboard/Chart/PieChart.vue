<template>
  <div>
    <canvas ref="donutChart"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const donutChart = ref(null);
let chartInstance = null;

const data = {
  labels: ['Red', 'Blue', 'Yellow'],
  datasets: [
    {
      label: 'My First Dataset',
      data: [300, 50, 100],
      backgroundColor: [
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
      ],
      hoverOffset: 4
    }
  ]
};

const config = {
  type: 'doughnut', // Chart.js uses 'doughnut' instead of 'donut'
  data: data,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  }
};

onMounted(() => {
  if (donutChart.value) {
    chartInstance = new Chart(donutChart.value, config);
  }
});

onBeforeUnmount(() => {
  if (chartInstance) {
    chartInstance.destroy();
  }
});
</script>
