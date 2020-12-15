<template>
    <div>
        <span
            v-if="template === 'number'"
            class="userCreditsNumber"
        >
            {{ credits }}
        </span>

        <span
            v-if="template === 'text'"
            class="Hero__button__creditsCount"
        >
            {{ credits }}  <span class="hidden-xs">Credits</span>
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