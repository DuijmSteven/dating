<template>
    <div :class="'PrivateChatItem__message PrivateChatItem__message--' + message.user">
        {{ message.text }}
    </div>
</template>

<script>
    export default {
        props: [
            'message'
        ],
    };
</script>
