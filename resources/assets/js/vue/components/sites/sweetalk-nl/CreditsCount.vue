<template>
    <span v-if="userCredits !== undefined" class="userCreditsNumber">
        <span class="userCreditsText" v-if="userCredits !== undefined">Credit{{ userCredits !== 1 ? 's' : '' }}:</span> <span>{{
            userCredits
        }}</span>
    </span>
</template>

<script>
import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";
import { requestConfig } from '../../../common-imports';

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
            axios.get(
                '/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits',
                requestConfig
            ).then(
                response => {
                    this.userCredits = response.data;
                    this.$root.$emit('userCreditsUpdated', {credits: this.userCredits});
                }
            );
        },
    }
}
</script>