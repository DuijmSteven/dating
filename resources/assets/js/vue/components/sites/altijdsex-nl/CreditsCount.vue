<template>
    <span class="userCreditsNumber">
        <span>{{ credits }}</span> <span class="userCreditsText">credit{{ userCredits !== 1 ? 's' : '' }}</span>
    </span>
</template>

<script>

export default {
    props: [
        'credits',
    ],
}
</script>