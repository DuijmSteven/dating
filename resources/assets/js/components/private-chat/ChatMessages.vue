<template>
    <div class="PrivateChatItem__body__content">
        <div v-for="message in messages">
            <div class="PrivateChatItem__message PrivateChatItem__message--user-a">{{ message.message }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages']
    };
</script>
