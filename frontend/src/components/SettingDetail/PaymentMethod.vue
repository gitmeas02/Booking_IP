<template>
    <div class="payment-methods">
        <h2 class="title">Payment methods</h2>
        <p class="subtitle">
            Securely add or remove payment methods to make it easier when you book.
        </p>

        <div class="payment-card-row">
            <div class="payment-card-info">
                <span class="label">Payment card</span>
                <span class="description">Pay with new card</span>
            </div>
            <button class="add-card" @click="addCard">Add card</button>
        </div>
    </div>
</template>

<script setup>
function addCard() {
    alert('Add card clicked');
}
</script>

<style scoped>
.payment-methods {
    background-color: #ffffff;
    padding: 0px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
}

.title {
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 4px 0;
}

.subtitle {
    color: #666;
    margin-bottom: 16px;
    font-size: 14px;
}

.payment-card-row {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 16px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-card-info {
    display: flex;
    flex-direction: column;
}

.label {
    font-weight: 600;
    font-size: 16px;
}

.description {
    color: #888;
    font-size: 14px;
}

.add-card {
    background: none;
    border: none;
    color: #0070f3;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
}

.add-card:hover {
    text-decoration: underline;
}

/* Responsive styles */
@media (max-width: 768px) {
    .payment-methods {
        padding: 12px;
    }

    .title {
        font-size: 18px;
    }

    .subtitle {
        font-size: 12px;
    }

    .payment-card-row {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px 0;
    }

    .add-card {
        font-size: 16px;
        margin-top: 8px;
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

    .description {
        font-size: 12px;
    }

    .add-card {
        font-size: 14px;
    }
}
</style>
