<!-- components/PersonalDetail/SecuritySettings.vue -->
<template>
    <div>
        <div class="header">
            <h2>Security settings</h2>
            <p>Change your security settings, set up secure authentication, or delete your account.</p>
        </div>
        <div class="item">
            <div>
                <strong>Two-factor authentication</strong>
                <p>Increase your account’s security by setting up two-factor authentication.</p>
            </div>
            <a href="#" class="link">Set up</a>
        </div>
        <div class="item">
            <div>
                <strong>Active sessions</strong>
                <p>Signing out will log you out from all devices except this one. This can take up to 10 minutes.</p>
            </div>
            <a href="#" class="link">Sign out</a>
        </div>
        <div class="item">
            <div>
                <strong>Delete account</strong>
                <p>Permanently delete your account</p>
            </div>
            <a href="#" class="link delete">Delete account</a>
        </div>
    </div>
</template>

<style scoped>
.header h2 {
    margin: 0;
    font-size: 22px;
}

.header p {
    color: #666;
    margin-bottom: 24px;
    font-size: 14px;
}

.item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 0;
    border-top: 1px solid #eee;
    gap: 16px;
}

.item p {
    margin: 4px 0 0;
    font-size: 14px;
    color: #666;
}

.link {
    color: #0071c2;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
}

.link.delete {
    color: red;
}

/* Responsive layout for small screens */
@media (max-width: 640px) {
    .item {
        flex-direction: column;
        align-items: flex-start;
    }

    .link {
        margin-top: 8px;
        font-size: 15px;
    }
}
</style>