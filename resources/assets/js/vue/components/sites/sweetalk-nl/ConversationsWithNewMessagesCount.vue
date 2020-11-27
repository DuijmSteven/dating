<template>
    <span
        class="ConversationsWithNewMessagesCount"
    >
        {{ count }} Berichten
    </span>
</template>

<script>

export default {
    props: [
        'count',
    ],
}
</script>