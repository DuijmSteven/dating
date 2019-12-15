<template>
    <span>{{ userCredits }}</span>
</template>

<script>
    import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

    export default {
        data() {
            return {
                userCredits: undefined
            }
        },

        mounted() {
            this.$root.$on('updateUserCredits', () => {
                this.getUserCredits();
            })
        },

        created() {
            if (!(isUndefined(DP.authenticatedUser) || DP.authenticatedUser == null)) {
                this.getUserCredits();
            }
        },

        methods: {
            getUserCredits: function () {
                axios.get('/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits').then(
                    response => {
                        this.userCredits = response.data;
                    }
                );
            },
        }
    }
</script>