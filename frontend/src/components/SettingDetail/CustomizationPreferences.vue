<template>
    <div class="container-1">
        <div class="header">
            <h2 class="title">Customization preferences</h2>
            <p class="subtitle">Personalize your account to meet your needs.</p>
        </div>

        <div class="preference-row">
            <span class="label">Currency</span>
            <span class="value">$$$</span>
            <button class="edit-btn">Edit</button>
        </div>

        <div class="preference-row">
            <span class="label">Language</span>
            <span class="value">American English</span>
            <button class="edit-btn">Edit</button>
        </div>
    </div>
</template>

<script setup>
// No logic yet, but you can later add emit or click handler
</script>

<style scoped>
.container-1 {
  background-color: #ffffff;
  padding: 0;
  border-radius: 8px;
  font-family: sans-serif;
}

.header {
  display: flex;
  flex-direction: column;
}

.title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.subtitle {
  color: #6b7280;
  margin-bottom: 1rem;
}

.preference-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #e5e7eb;
  padding: 1rem 0;
  gap: 1rem;
}

.label {
  font-weight: 500;
  flex: 1;
}

.value {
  color: #4b5563;
  flex: 1;
  text-align: left;
}

.edit-btn {
  color: #2563eb;
  background: none;
  border: none;
  font-weight: 500;
  cursor: pointer;
}

.edit-btn:hover {
  text-decoration: underline;
}

/* 🔁 Responsive for small screens */
@media (max-width: 640px) {
  .preference-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .label,
  .value,
  .edit-btn {
    width: 100%;
  }

  .edit-btn {
    margin-top: 0.5rem;
    padding: 0.5rem 0;
    text-align: left;
  }
}

</style>