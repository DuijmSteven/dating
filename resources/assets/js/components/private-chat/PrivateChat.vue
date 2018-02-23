<template>
    <div class="PrivateChatItem">
        <div class="PrivateChatItem__head">
            <div class="PrivateChatItem__head__wrapper">
                <div class="PrivateChatItem__user">
                    <img class="PrivateChatItem__profile-image" src="http://placehold.it/40x40">
                    <div class="PrivateChatItem__username">{{ partner.username }}</div>
                </div>

                <div class="PrivateChatItem__clear"><i class="material-icons material-icon clear">clear</i></div>
            </div>
        </div>

        <div class="PrivateChatItem__body">
            <div class="PrivateChatItem__body__wrapper">
                <chat-messages :messages="messages"></chat-messages>
                <chat-form
                        v-on:messagesent="addMessage"
                        :user="user"
                ></chat-form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
            'partner'
        ],

        data: {

        },

        data() {
            return {
                newMessage: ''
            }
        },

        created() {
            this.fetchMessages();
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    message: this.newMessage
                });

                this.newMessage = ''
            },

            fetchMessages: function () {
                axios.get('/api/conversations/' + this.user.id + '/' + this.partner.id).then(response => {

                    console.log(response);

                    for (let i = 0; i < response.data.length; i++) {
                        this.messages.push({
                            message: response.data[i].body,
                            user: response.data[i].sender.username
                        });
                    }
                });
            },

            addMessage: function (message) {
                axios.post('/conversations', {
                    message: message.message,
                    conversation_id: '1',
                    sender_id: '44',
                    recipient_id: '83'
                })
            }
        }
    }
</script>