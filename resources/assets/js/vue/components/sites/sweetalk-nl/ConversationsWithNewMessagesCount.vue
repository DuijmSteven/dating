<template>
    <span
        class="ConversationsWithNewMessagesCount"
    >
        {{ count }} <span class="hidden-xs">Berichten</span>
    </span>
</template>

<script>

export default {
    props: [
        'count',
    ],
}
</script>