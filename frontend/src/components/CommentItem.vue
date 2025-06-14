<template>
  <div class="comment-item">
    <div class="comment-content">
      <div class="comment-header">
        <div class="user-info">
          <div class="user-avatar" :class="{ 'staff-avatar': comment.isStaff }">
            <span class="avatar-text">{{ comment.avatar }}</span>
          </div>
          <div class="user-details">
            <div class="user-name">
              {{ comment.user }}
              <span v-if="comment.isStaff" class="staff-badge">Staff</span>
            </div>
            <div class="comment-date">{{ comment.date }}</div>
          </div>
        </div>
        <div v-if="comment.rating" class="user-rating">
          <span class="rating-score">{{ comment.rating }}</span>
        </div>
      </div>

      <div class="comment-text">{{ comment.text }}</div>

      <div class="comment-actions">
        <button class="action-btn" @click="toggleReply">
          <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M9 17H15L20 22V4C20 2.89 19.1 2 18 2H6C4.9 2 4 2.89 4 4V16C4 17.11 4.9 18 6 18H9V17Z"
              stroke="currentColor"
              stroke-width="2"
            />
          </svg>
          Reply
        </button>
        <button class="action-btn">
          <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M14 9V5C14 3.89 13.1 3 12 3S10 3.89 10 5V9C8.34 9 7 10.34 7 12S8.34 15 10 15H14C15.66 15 17 13.66 17 12S15.66 9 14 9Z"
              stroke="currentColor"
              stroke-width="2"
            />
          </svg>
          Helpful
        </button>
      </div>

      <!-- Reply Input -->
      <div v-if="showReply" class="reply-input-section">
        <div class="reply-input-container">
          <div class="user-avatar small">
            <span class="avatar-text">Y</span>
          </div>
          <div class="reply-wrapper">
            <textarea
              v-model="replyText"
              placeholder="Write a reply..."
              class="reply-input"
              rows="2"
            ></textarea>
            <div class="reply-actions">
              <button @click="cancelReply" class="cancel-btn">Cancel</button>
              <button
                @click="submitReply"
                class="submit-reply-btn"
                :disabled="!replyText.trim()"
              >
                Reply
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Nested Replies -->
    <div
      v-if="comment.replies && comment.replies.length > 0"
      class="replies-section"
    >
      <CommentItem
        v-for="reply in comment.replies"
        :key="reply.id"
        :comment="reply"
        :is-reply="true"
        @reply="$emit('reply', $event)"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  comment: {
    type: Object,
    required: true,
  },
  isReply: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["reply"]);

const showReply = ref(false);
const replyText = ref("");

function toggleReply() {
  showReply.value = !showReply.value;
  if (!showReply.value) {
    replyText.value = "";
  }
}

function cancelReply() {
  showReply.value = false;
  replyText.value = "";
}

function submitReply() {
  if (!replyText.value.trim()) return;

  emit("reply", {
    parentId: props.comment.id,
    replyText: replyText.value,
  });

  replyText.value = "";
  showReply.value = false;
}
</script>

<style scoped>
.comment-item {
  margin-bottom: 20px;
}

.comment-content {
  background: white;
  border: 1px solid #e7e7e7;
  border-radius: 8px;
  padding: 16px;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: #6b6b6b;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.user-avatar.small {
  width: 32px;
  height: 32px;
}

.staff-avatar {
  background: #ff8c00 !important;
}

.avatar-text {
  color: white;
  font-weight: 600;
  font-size: 14px;
}

.user-details {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  font-size: 14px;
  color: #262626;
  display: flex;
  align-items: center;
  gap: 8px;
}

.staff-badge {
  background: #ff8c00;
  color: white;
  font-size: 10px;
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 500;
  text-transform: uppercase;
}

.comment-date {
  font-size: 12px;
  color: #6b6b6b;
}

.user-rating {
  background: #003580;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
}

.comment-text {
  font-size: 14px;
  line-height: 1.5;
  color: #262626;
  margin-bottom: 12px;
}

.comment-actions {
  display: flex;
  gap: 16px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  background: none;
  border: none;
  color: #6b6b6b;
  font-size: 12px;
  cursor: pointer;
  padding: 4px 0;
  transition: color 0.2s;
}

.action-btn:hover {
  color: #0071c2;
}

.reply-input-section {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid #f0f0f0;
}

.reply-input-container {
  display: flex;
  gap: 8px;
  align-items: flex-start;
}

.reply-wrapper {
  flex: 1;
}

.reply-input {
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px;
  font-size: 12px;
  font-family: inherit;
  resize: vertical;
  transition: border-color 0.2s;
}

.reply-input:focus {
  outline: none;
  border-color: #0071c2;
}

.reply-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  margin-top: 8px;
}

.cancel-btn {
  background: none;
  border: 1px solid #ddd;
  color: #6b6b6b;
  padding: 4px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  transition: all 0.2s;
}

.cancel-btn:hover {
  border-color: #bbb;
  color: #333;
}

.submit-reply-btn {
  background: #0071c2;
  color: white;
  border: none;
  padding: 4px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  transition: background 0.2s;
}

.submit-reply-btn:hover:not(:disabled) {
  background: #005999;
}

.submit-reply-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.replies-section {
  margin-left: 32px;
  margin-top: 16px;
  padding-left: 16px;
  border-left: 2px solid #f0f0f0;
}

/* Responsive */
@media (max-width: 768px) {
  .replies-section {
    margin-left: 16px;
    padding-left: 12px;
  }

  .reply-input-container {
    flex-direction: column;
  }

  .user-avatar.small {
    align-self: flex-start;
  }
}
</style>
