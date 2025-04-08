<template>
    <div class="privacy-policy">
        <h2 class="title">Privacy and Policy</h2>
        <p class="subtitle">
            Exercise your privacy rights, control your data , or export your information.
        </p>

        <div class="privacy-row">
            <div class="privacy-info">
                <span class="label">Privacy setting</span>
                <div class="details">
                    <span class="email">khunmeas@gmail.com</span>
                    <p class="note">
                        Select “Manage” to change your privacy setting and exercise your rights using our request form.
                    </p>
                </div>
            </div>
            <button class="manage-btn" @click="managePrivacy">Manage</button>
        </div>
    </div>
</template>

<script setup>
function managePrivacy() {
    alert('Manage privacy clicked');
}
</script>

<style scoped>
.privacy-policy {
    background-color: #ffffff;
    padding: 16px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
}

.title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 4px;
}

.subtitle {
    color: #666;
    font-size: 14px;
    margin-bottom: 16px;
}

.privacy-row {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 16px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.privacy-info {
    display: flex;
    flex-direction: column;
}

.label {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 4px;
}

.details {
    display: flex;
    flex-direction: column;
}

.email {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 2px;
}

.note {
    font-size: 13px;
    color: #666;
    max-width: 400px;
    margin: 0;
}

.manage-btn {
    background: none;
    border: none;
    color: #0070f3;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    padding: 0;
}

.manage-btn:hover {
    text-decoration: underline;
}

/* Responsive styles */
@media (max-width: 768px) {
    .privacy-policy {
        padding: 12px;
    }

    .title {
        font-size: 18px;
    }

    .subtitle {
        font-size: 12px;
    }

    .privacy-row {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px 0;
    }

    .manage-btn {
        font-size: 16px;
        margin-top: 8px;
    }

    .note {
        max-width: none; /* Allow note to take up more space */
    }
}

@media (max-width: 480px) {
    .title {
        font-size: 16px;
    }

    .subtitle {
        font-size: 12px;
    }

    .label {
        font-size: 14px;
    }

    .email {
        font-size: 14px;
    }

    .note {
        font-size: 12px;
    }

    .manage-btn {
        font-size: 14px;
    }
}
</style>
