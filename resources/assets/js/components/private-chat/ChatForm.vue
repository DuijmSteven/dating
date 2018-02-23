<template>
    <textarea class="PrivateChatItem__textarea" rows="4"
              placeholder="Type your message here..."
              v-model="newMessage"
              @keyup.enter="sendMessage"></textarea>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    message: this.newMessage
                });

                this.newMessage = ''
            }
        }
    }
</script>