<template>
    <span
        class="ConversationsWithNewMessagesCount"
    >
        {{ count }}
    </span>
</template>

<script>

export default {
    props: [
        'count',
    ],
}
</script>