<template>
  <div>
    <canvas ref="donutChart"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  chartData: {
    type: Object,
    required: true
  }
});

const donutChart = ref(null);
let chartInstance = null;

const config = {
  type: 'doughnut',
  data: props.chartData,
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

// Update chart when chartData changes
watch(
  () => props.chartData,
  (newData) => {
    if (chartInstance) {
      chartInstance.data = newData;
      chartInstance.update();
    }
  },
  { deep: true }
);
</script>