<template>
  <div class="double-slider-box">
    <h3 class="range-title">Filter Price</h3>

    <div class="range-slider">
      <div class="slider-track" :style="trackStyle"></div>

      <!-- Min Slider -->
      <input
        type="range"
        :min="fixedMin"
        :max="maxValue - step"
        v-model.number="minValue"
        class="thumb thumb-left"
        @input="handleMinChange"
      />

      <!-- Max Slider -->
      <input
        type="range"
        :min="minValue + step"
        :max="fixedMax"
        v-model.number="maxValue"
        class="thumb thumb-right"
        @input="handleMaxChange"
      />
    </div>

    <div class="value-display">
      <span class="value">
        <div class="min">MIN</div>
        <div class="value-box">
          <span class="currency">USD</span>
          <span class="price">{{ minValue }}</span>
        </div>
      </span>
      <span class="value">
        <div class="max">Max</div>
        <div class="value-box">
          <span class="currency">USD</span>
          <span class="price">{{ maxValue }}</span>
        </div>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "DoubleSlider",
  data() {
    return {
      fixedMin: 5,
      fixedMax: 120,
      step: 1,
      minValue: 5,
      maxValue: 120,
    };
  },
  computed: {
    trackStyle() {
      const range = this.fixedMax - this.fixedMin;
      const leftPercent = ((this.minValue - this.fixedMin) / range) * 100;
      const rightPercent = 100 - ((this.maxValue - this.fixedMin) / range) * 100;
      return {
        left: `${leftPercent}%`,
        right: `${rightPercent}%`,
      };
    },
  },
  methods: {
    handleMinChange() {
      if (this.minValue > this.maxValue - this.step) {
        this.minValue = this.maxValue - this.step;
      }
    },
    handleMaxChange() {
      if (this.maxValue < this.minValue + this.step) {
        this.maxValue = this.minValue + this.step;
      }
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

.double-slider-box {
  background-color: #fff;
  border-radius: 10px;
  max-width: 100%;
  width: 100%;
  padding: 1rem;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

.range-title {
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
  text-align: center;
}

.range-slider {
  position: relative;
  height: 5px;
  background-color: #8a8a8a;
  margin: 30px 0;
}

.slider-track {
  position: absolute;
  height: 100%;
  background-color: #9a9a9a;
  z-index: 1;
  border-radius: 5px;
  pointer-events: none;
}

input[type="range"] {
  position: absolute;
  width: 100%;
  top: 50%;
  transform: translateY(-50%);
  appearance: none;
  background: none;
  pointer-events: none;
}

.thumb-left,
.thumb-right {
  z-index: 3;
}

input[type="range"]::-webkit-slider-thumb {
  appearance: none;
  pointer-events: auto;
  height: 20px;
  width: 20px;
  background: white;
  border: 2px solid gray;
  border-radius: 50%;
  cursor: pointer;
}

input[type="range"]::-moz-range-thumb {
  pointer-events: auto;
  height: 20px;
  width: 20px;
  background: white;
  border: 2px solid gray;
  border-radius: 50%;
  cursor: pointer;
}

.value-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.value {
  font-size: 1rem;
  flex: 1 1 45%;
}

.min,
.max {
  padding: 0.25rem 0.5rem;
  text-align: left;
  font-weight: 600;
}

.value-box {
  display: flex;
  align-items: center;
  border: 1px solid black;
  width: 100%;
  max-width: 120px;
  margin-top: 0.25rem;
}

.currency {
  padding: 0.25rem;
  white-space: nowrap;
}

.price {
  flex: 1;
  text-align: center;
  border-left: 1px solid black;
  padding: 0.25rem;
}

/* Responsive Tweaks */
@media (max-width: 480px) {
  .range-title {
    font-size: 1rem;
  }

  .value-display {
    flex-direction: column;
    align-items: flex-start;
  }

  .value-box {
    max-width: 100%;
  }
}

</style>
