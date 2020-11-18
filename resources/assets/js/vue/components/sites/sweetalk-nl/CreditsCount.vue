<template>
    <div>
        <span
            v-if="template === 'text'"
            class="userCreditsNumber"
        >
            <span class="userCreditsText">Credit{{ userCredits !== 1 ? 's' : '' }}: {{ credits }}</span>
        </span>

        <span
            v-if="template === 'disk'"
            class="Hero__button__creditsCount"
        >
            {{ credits }}
        </span>
    </div>


</template>

<script>

export default {
    props: [
        'credits',
        'template'
    ],
}
</script>