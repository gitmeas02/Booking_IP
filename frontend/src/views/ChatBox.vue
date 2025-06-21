<template>
  <div class="chat-container">
    <div class="sidebar" :class="{ 'sidebar-hidden': !showSidebar }">
      <button class="toggle-sidebar" @click="showSidebar = !showSidebar">
        <Icon
          :icon="showSidebar ? 'ep:arrow-left-bold' : 'ep:arrow-right-bold'"
        ></Icon>
      </button>
      <div class="search-bar">
        <button class="back-button" @click="goBack">
          <Icon icon="ep:arrow-left-bold"></Icon>
          <p>Back</p>
        </button>
        <input type="text" placeholder="Search" class="search-input" />
      </div>
      <div class="contacts-list">
        <div
          v-for="(contact, index) in contacts"
          :key="index"
          class="contact"
          @click="selectContact(contact)"
        >
          <div class="avatar"></div>
          <div class="contact-info">
            <div class="contact-name">{{ contact.name }}</div>
            <div class="contact-status">{{ contact.status || "Active" }}</div>
          </div>
          <div class="time">{{ contact.time || "" }}</div>
          <div class="unread-badge" v-if="contact.unread > 0">
            {{ contact.unread }}
          </div>
        </div>
      </div>
    </div>

    <div class="chat-area">
      <div class="chat-header">
        <div class="chat-title">
          <span>{{ selectedContact || "Select a contact" }}</span>
          <span class="status-indicator" v-if="selectedContact">Online</span>
        </div>
      </div>

      <div class="messages" ref="messagesContainer">
        <div v-if="loading" class="loading">Loading messages...</div>
        <div v-else-if="messages.length === 0">No messages yet.</div>
        <div
          v-else
          v-for="(message, index) in messages"
          :key="index"
          :class="[
            'message',
            message.sender === 'user' ? 'message-right' : 'message-left',
          ]"
          v-html="formatMessage(message.text)"
        >   
        
    </div>
      </div>

      <div class="message-input-container">
        <textarea
          placeholder="Type message..."
          class="message-input"
          v-model="newMessage"
          @input="autoResize"
          @keydown.enter.prevent="handleEnter"
          rows="1"
          ref="messageInput"
        ></textarea>
        <button
          class="send-button"
          @click="sendMessage"
          :disabled="!selectedContactId"
        >
          <button class="send-button" @click="sendMessage">
            <p>Send</p>
            <Icon icon="proicons:send"></Icon>
          </button>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from "@iconify/vue";
import axios from "axios";
import { onMounted, ref } from "vue";

export default {
  components: {
    Icon,
  },
  setup() {
    const contacts = ref([]);
    const currentUserId = ref(null);

    onMounted(async () => {
      try {
        const userRes = await axios.get("/api/user", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        currentUserId.value = userRes.data.id;

        const contactsRes = await axios.get("/api/chat-partners", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        contacts.value = contactsRes.data;
      } catch (e) {
        console.error("Failed to load data:", e);
        this.$toast.error("Failed to load data");
      }
    });

    return {
      contacts,
      currentUserId,
    };
  },
  data() {
    return {
      selectedContact: null,
      selectedContactId: null,
      newMessage: "",
      messages: [],
      loading: false,
      showSidebar: window.innerWidth > 768,
    };
  },
  methods: {
    async selectContact(contact) {
      this.loading = true;
      this.selectedContact = contact.name;
      this.selectedContactId = contact.id;
      try {
        const response = await axios.get(
          `/api/messages?user_id=${contact.id}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        this.messages = response.data.map((msg) => ({
          sender: msg.sender_id === this.currentUserId ? "user" : "other",
          text: msg.content,
        }));
        this.$nextTick(() => this.scrollToBottom());
      } catch (error) {
        this.$toast.error("Failed to load messages");
        console.error("Failed to load messages:", error);
      } finally {
        this.loading = false;
      }
    },
    async sendMessage() {
      if (this.newMessage.trim() && this.selectedContactId) {
        try {
          const response = await axios.post(
            "/api/messages",
            {
              receiver_id: this.selectedContactId,
              content: this.newMessage,
            },
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
              },
            }
          );
          this.messages.push({
            sender: "user",
            text: this.newMessage,
          });
          this.newMessage = "";
          this.$nextTick(() => {
            this.scrollToBottom();
            const textarea = this.$refs.messageInput;
            if (textarea) {
              textarea.style.height = "auto";
            }
          });
        } catch (error) {
          this.$toast.error("Failed to send message");
          console.error("Failed to send message:", error);
        }
      }
    },
    autoResize(event) {
      const textarea = event.target;
      textarea.style.height = "auto";
      const maxHeight = 10 * 24;
      textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + "px";
    },
    handleEnter(event) {
      if (!event.shiftKey) {
        this.sendMessage();
      } else {
        const cursorPosition = event.target.selectionStart;
        this.newMessage =
          this.newMessage.substring(0, cursorPosition) +
          "\n" +
          this.newMessage.substring(cursorPosition);
        this.$nextTick(() => {
          event.target.selectionStart = cursorPosition + 1;
          event.target.selectionEnd = cursorPosition + 1;
        });
      }
    },
    scrollToBottom() {
      const container = this.$refs.messagesContainer;
      container.scrollTop = container.scrollHeight;
    },
    formatMessage(text) {
      return text.replace(/\n/g, "<br>");
    },
    goBack() {
      this.$router.back();
    },
  },
};
</script>
<style scoped>
.chat-container {
  display: flex;
  height: 100vh;
  width: 100%;
  font-family: Arial, sans-serif;
  border: 1px solid #e0e0e0;
}

.sidebar {
  width: 30%;
  border-right: 1px solid #e0e0e0;
  display: flex;
  flex-direction: column;
  background-color: white;
}

.search-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 10px;
  border-bottom: 1px solid #e0e0e0;
}

.search-input {
  width: 90%;
  padding: 8px;
  border-radius: 15px;
  border: 1px solid #e0e0e0;
  outline: none;
}

.back-button {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 0 10px;
}

.contacts-list {
  flex: 1;
  overflow-y: auto;
}

.contact {
  display: flex;
  align-items: center;
  padding: 10px;
  cursor: pointer;
  position: relative;
  border-bottom: 1px solid #f5f5f5;
}

.contact:hover {
  background-color: #f5f5f5;
}

.avatar {
  width: 40px;
  height: 40px;
  background-color: #e0e0e0;
  border-radius: 50%;
  margin-right: 10px;
}

.contact-info {
  flex: 1;
}

.contact-name {
  font-weight: bold;
}

.contact-status {
  font-size: 0.8em;
  color: #888;
}

.time {
  font-size: 0.7em;
  color: #888;
}

.unread-badge {
  background-color: white;
  border: 1px solid #888;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7em;
  margin-left: 5px;
}

.chat-area {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.chat-header {
  height: 63px;
  padding: 15px;
  border-bottom: 1px solid #e0e0e0;
  background-color: white;
}

.chat-title {
  font-weight: bold;
}

.status-indicator {
  font-size: 0.8em;
  color: #888;
  margin-left: 10px;
  font-weight: normal;
}

.messages {
  flex: 1;
  padding: 15px;
  overflow-y: auto;
  background-color: #ffffff;
}

.message {
  max-width: 50%;
  padding: 10px 20px;
  margin-bottom: 10px;
  border-radius: 15px;
  line-height: 1.4;
  word-wrap: break-word;
}

.message-left {
  background-color: rgb(156, 156, 156);
  align-self: flex-start;
  float: left;
  clear: both;
}

.message-right {
  background-color: rgb(156, 156, 156);
  align-self: flex-end;
  float: right;
  clear: both;
}

.message-input-container {
  display: flex;
  align-items: end;
  padding: 30px;
  background-color: white;
  border-top: 1px solid #e0e0e0;
}

.message-input {
  flex: 1;
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 20px;
  outline: none;
  margin-right: 10px;
  resize: none;
}

.send-button {
  background-color: #8b3a3a;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 0 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 120px;
  height: 50px;
  gap: 10px;
}

.send-button:hover {
  background-color: #6d2d2d;
}
</style>
