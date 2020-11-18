<template>
    <span class="userCreditsNumber">
        <span class="userCreditsText">Credit{{ credits !== 1 ? 's' : '' }}:</span> <span>{{
            credits
        }}</span>
    </span>
</template>

<script>

export default {
    props: [
        'credits',
    ],
}
</script>