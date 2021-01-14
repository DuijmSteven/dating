<template>
    <span
        class="ConversationsWithNewMessagesCount"
    >
        {{ count }}&nbsp;<span class="hidden-xs">Berichten</span>
    </span>
</template>

<script>

export default {
    props: [
        'count',
    ],
}
</script>