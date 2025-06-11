<template>
  <div class="comment-section-container">
    <div class="comment-header">
      <h2 class="comment-title">Guest Reviews</h2>
      <div class="review-summary">
        <div class="overall-rating">
          <span class="rating-score">8.7</span>
          <div class="rating-info">
            <div class="rating-label">Excellent</div>
            <div class="review-count">{{ comments.length }} reviews</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add New Comment -->
    <div class="add-comment-section">
      <div class="comment-input-container">
        <div class="user-avatar">
          <span class="avatar-text">You</span>
        </div>
        <div class="input-wrapper">
          <textarea
            v-model="newComment"
            placeholder="Share your experience about this hotel..."
            class="comment-input"
            rows="3"
          ></textarea>
          <div class="input-actions">
            <button
              @click="addComment"
              class="submit-btn"
              :disabled="!newComment.trim()"
            >
              Post Review
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Comments List -->
    <div class="comments-list">
      <CommentItem
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
        @reply="handleReply"
      />
    </div>

    <!-- Load More Comments -->
    <div v-if="comments.length > 0" class="load-more-section">
      <button class="load-more-btn">Load More Reviews</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import CommentItem from "@/components/CommentItem.vue";

// Sample comments data
const comments = ref([
  {
    id: 1,
    user: "Khun Meas",
    avatar: "KM",
    text: "All staff are friendly and helpful. I would like to recommend friends and family to stay there. The room was clean and the location is perfect.",
    date: "January 17, 2024",
    rating: 9.2,
    replies: [
      {
        id: 11,
        user: "Hotel Manager",
        avatar: "HM",
        text: "Thank you so much for your wonderful review! We are delighted to hear that you enjoyed your stay with us.",
        date: "January 18, 2024",
        isStaff: true,
        replies: [],
      },
    ],
  },
  {
    id: 2,
    user: "Sarah Johnson",
    avatar: "SJ",
    text: "Great location and excellent service. The breakfast was amazing and the room had a beautiful view. Will definitely come back!",
    date: "January 15, 2024",
    rating: 8.8,
    replies: [],
  },
  {
    id: 3,
    user: "David Chen",
    avatar: "DC",
    text: "Clean rooms, friendly staff, and great value for money. The hotel is conveniently located near many attractions.",
    date: "January 10, 2024",
    rating: 8.5,
    replies: [],
  },
]);

const newComment = ref("");

function addComment() {
  if (!newComment.value.trim()) return;

  comments.value.unshift({
    id: Date.now(),
    user: "You",
    avatar: "Y",
    text: newComment.value,
    date: new Date().toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    }),
    rating: null, // User hasn't rated yet
    replies: [],
  });
  newComment.value = "";
}

function handleReply({ parentId, replyText }) {
  const findAndReply = (arr) => {
    for (const c of arr) {
      if (c.id === parentId) {
        c.replies.unshift({
          id: Date.now() + Math.random(),
          user: "You",
          avatar: "Y",
          text: replyText,
          date: new Date().toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          }),
          replies: [],
        });
        return true;
      }
      if (c.replies && findAndReply(c.replies)) return true;
    }
    return false;
  };
  findAndReply(comments.value);
}
</script>

<style scoped>
.comment-section-container {
  max-width: 800px;
  margin: 32px 0;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    "Helvetica Neue", Arial, sans-serif;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 2px solid #e7e7e7;
}

.comment-title {
  font-size: 24px;
  font-weight: 600;
  margin: 0;
  color: #262626;
}

.review-summary {
  display: flex;
  align-items: center;
}

.overall-rating {
  display: flex;
  align-items: center;
  gap: 12px;
}

.rating-score {
  background: #003580;
  color: white;
  font-size: 20px;
  font-weight: bold;
  padding: 8px 12px;
  border-radius: 6px;
  min-width: 50px;
  text-align: center;
}

.rating-info {
  text-align: left;
}

.rating-label {
  font-size: 14px;
  font-weight: 600;
  color: #262626;
}

.review-count {
  font-size: 12px;
  color: #6b6b6b;
}

.add-comment-section {
  margin-bottom: 32px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px solid #e7e7e7;
}

.comment-input-container {
  display: flex;
  gap: 12px;
  align-items: flex-start;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: #0071c2;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.avatar-text {
  color: white;
  font-weight: 600;
  font-size: 14px;
}

.input-wrapper {
  flex: 1;
}

.comment-input {
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 12px;
  font-size: 14px;
  font-family: inherit;
  resize: vertical;
  min-height: 80px;
  transition: border-color 0.2s;
}

.comment-input:focus {
  outline: none;
  border-color: #0071c2;
  box-shadow: 0 0 0 2px rgba(0, 113, 194, 0.1);
}

.comment-input::placeholder {
  color: #999;
}

.input-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 8px;
}

.submit-btn {
  background: #0071c2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

.submit-btn:hover:not(:disabled) {
  background: #005999;
}

.submit-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.comments-list {
  space-y: 20px;
}

.load-more-section {
  text-align: center;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid #e7e7e7;
}

.load-more-btn {
  background: transparent;
  color: #0071c2;
  border: 2px solid #0071c2;
  padding: 10px 24px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.2s;
}

.load-more-btn:hover {
  background: #0071c2;
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .comment-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .comment-input-container {
    flex-direction: column;
  }

  .user-avatar {
    align-self: flex-start;
  }
}
</style>
