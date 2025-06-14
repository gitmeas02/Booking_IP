<template>
  <div class="qr-code-container">
    <!-- Enhanced QR Code with Custom Overlay -->
    <div class="relative inline-block">
      <canvas
        ref="qrCanvas"
        :width="size"
        :height="size + 60"
        class="qr-canvas"
      ></canvas>

      <!-- Loading overlay -->
      <div
        v-if="isLoading"
        class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-lg"
      >
        <div
          class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"
        ></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from "vue";

const props = defineProps({
  qrString: {
    type: String,
    required: true,
  },
  amount: {
    type: Number,
    required: true,
  },
  currency: {
    type: String,
    default: "KHR",
  },
  merchantName: {
    type: String,
    default: "Khun Hotel",
  },
  size: {
    type: Number,
    default: 200,
  },
  showOverlay: {
    type: Boolean,
    default: true,
  },
});

const qrCanvas = ref(null);
const isLoading = ref(false);

const generateEnhancedQR = async () => {
  if (!props.qrString || !qrCanvas.value) return;

  isLoading.value = true;

  try {
    const canvas = qrCanvas.value;
    const ctx = canvas.getContext("2d");

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Create QR code image
    const qrSize = props.size;
    const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(
      props.qrString
    )}&format=png&margin=10`;

    const img = new Image();
    img.crossOrigin = "anonymous";

    img.onload = () => {
      // Draw QR code
      ctx.drawImage(img, 0, 30, qrSize, qrSize);

      if (props.showOverlay) {
        // Add currency symbol overlay in center
        const centerX = qrSize / 2;
        const centerY = 30 + qrSize / 2;
        const overlaySize = 40;

        // Draw white background circle for currency symbol
        ctx.fillStyle = "white";
        ctx.beginPath();
        ctx.arc(centerX, centerY, overlaySize / 2, 0, 2 * Math.PI);
        ctx.fill();

        // Draw border
        ctx.strokeStyle = "#3B82F6";
        ctx.lineWidth = 2;
        ctx.stroke();

        // Draw currency symbol
        ctx.fillStyle = "#1E40AF";
        ctx.font = "bold 20px Arial";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText("៛", centerX, centerY);

        // Add amount text above QR
        ctx.fillStyle = "#1E40AF";
        ctx.font = "bold 16px Arial";
        ctx.textAlign = "center";
        ctx.fillText(
          `${props.amount.toLocaleString()} ${props.currency}`,
          centerX,
          20
        );

        // Add merchant name below QR
        ctx.fillStyle = "#374151";
        ctx.font = "14px Arial";
        ctx.fillText(props.merchantName, centerX, qrSize + 50);
      }

      isLoading.value = false;
    };

    img.onerror = () => {
      console.error("Failed to load QR code image");
      isLoading.value = false;
    };

    img.src = qrImageUrl;
  } catch (error) {
    console.error("Error generating enhanced QR:", error);
    isLoading.value = false;
  }
};

// Watch for changes in QR string
watch(
  () => props.qrString,
  () => {
    nextTick(() => {
      generateEnhancedQR();
    });
  },
  { immediate: true }
);

onMounted(() => {
  generateEnhancedQR();
});

defineExpose({
  regenerate: generateEnhancedQR,
});
</script>

<style scoped>
.qr-code-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.qr-canvas {
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  background: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
