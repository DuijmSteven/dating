<template>
    <div class="NotificationsManager">
        <div v-on:click="toggle"
             class="NotificationsManager__head"
        >
            <div class="NotificationsManager__toggle">
                <div
                    class="NotificationsManager__toggle__text"
                    style="display: none"
                >
                    <span>Notifications (3)</span>

                    <i class="NotificationsManager__toggle__minimize material-icons"
                    >
                        minimize
                    </i>
                </div>
                <div class="NotificationsManager__toggle__icons">
                    <i class="material-icons">
                        notifications
                    </i>
                </div>
                <span class="NotificationsManager__toggle__icons NotificationsManager__newNotifications">3</span>
            </div>
        </div>
        <div
            id="NotificationsManager__body"
            class="NotificationsManager__body"
            style="display: none"
        >
            <div class="NotificationsManager__body__item">
                <span>Jane viewed your profile</span>
                <i class="material-icons">
                    close
                </i>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NotificationsManager",
        methods: {
            toggle: function () {
                $('#NotificationsManager__body').slideToggle('fast');
                $('.NotificationsManager').toggleClass('maximized')
                $('.NotificationsManager__toggle__text').toggle();
                $('.NotificationsManager__toggle__icons').toggle();
            }
        }
    }
</script>

<style scoped>

</style>
