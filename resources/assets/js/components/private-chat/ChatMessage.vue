<template>
    <div :class="'PrivateChatItem__message PrivateChatItem__message--' + message.user">
        {{ message.text }} <span class="PrivateChatItem__message__createdAt">{{ message.createdAt }}</span>
    </div>
</template>

<script>
    export default {
        props: [
            'message'
        ],
    };
</script>
