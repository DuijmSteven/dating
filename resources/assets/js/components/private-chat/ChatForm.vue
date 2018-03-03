<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <textarea class="PrivateChatItem__textarea" rows="10" cols="40"
                  placeholder="Type your message here..."
                  v-model.trim="text"
                  v-on:keyup.enter="sendMessage">
        </textarea>
        <div class="PrivateChatItem__textarea__buttons">
            <i class="material-icons">attach_file</i>
        </div>
    </div>
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