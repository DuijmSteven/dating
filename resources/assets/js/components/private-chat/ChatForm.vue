<template>
    <textarea class="PrivateChatItem__textarea" rows="4"
              placeholder="Type your message here..."
              v-model.trim="text"
              v-on:keyup.enter="sendMessage"></textarea>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                text: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('message-sent', {
                    text: this.text
                });

                this.text = ''
            }
        }
    }
</script>