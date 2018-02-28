<template>
    <div :class="'PrivateChatItem PrivateChatItem--' + index">
        <div class="PrivateChatItem__head">
            <div class="PrivateChatItem__head__wrapper">
                <div class="PrivateChatItem__user">
                    <img class="PrivateChatItem__profile-image" src="http://placehold.it/40x40">
                    <div class="PrivateChatItem__username">{{ partner.username }}</div>
                </div>

                <div :id="'PrivateChatItem__clear--' + index" class="PrivateChatItem__clear" v-on:click="clear"><i class="material-icons material-icon clear">clear</i></div>
            </div>
        </div>

        <div class="PrivateChatItem__body">
            <div class="PrivateChatItem__body__wrapper">
                <div class="PrivateChatItem__body__content">
                    <chat-message
                        v-for="(message, index) in messages"
                        :message="message">
                    </chat-message>
                </div>
                <chat-form
                    v-on:message-sent="addMessage"
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
            'partner',
            'index'
        ],

        data() {
            return {
                listening: false,
                messages: [],
                conversation: undefined,
                userAId: undefined,
                userBId: undefined
            };
        },

        created() {
            this.userAId = this.user.id;
            this.userBId = this.partner.id;

            this.fetchMessagesAndListenToChannel();
        },

        methods: {
            fetchMessagesAndListenToChannel: function () {
                axios.get('/api/conversations/' + this.userAId + '/' + this.userBId).then(response => {
                    this.conversation = response.data;

                    this.userBId = this.user.id === this.conversation.messages[0].sender_id ?
                        this.conversation.messages[0].recipient_id :
                        this.conversation.messages[0].sender_id;

                    for (let i = 0; i < this.conversation.messages.length; i++) {
                        this.messages.push({
                            id: this.conversation.messages[i].id,
                            text: this.conversation.messages[i].body,
                            user: this.conversation.messages[i].sender.id === this.user.id ? 'user-a' : 'user-b'
                        });
                    }

                    Echo.private('chat.' + this.conversation.id)
                        .listen('MessageSent', (e) => {
                            this.messages.push({
                                id: this.messages[this.messages.length - 1].id + 1,
                                text: e.conversationMessage.body,
                                user: 'user-a'
                            });
                        });
                    this.listening = true;

                }).catch(function (error) {
                    console.log(error.response.status);
                });
            },

            addMessage: function (message) {
                if (!this.listening) {
                    axios.post('/conversations', {
                        message: message.text,
                        sender_id: this.userAId,
                        recipient_id: this.userBId
                    }).then(function (response) {
                        axios.get('/api/conversations/' + this.userAId + '/' + this.userBId).then(response => {
                            this.conversation = response.data;

                            for (let i = 0; i < this.conversation.messages.length; i++) {
                                this.messages.push({
                                    id:  this.conversation.messages[i].id,
                                    text:  this.conversation.messages[i].body,
                                    user:  this.conversation.messages[i].sender.id === this.userAId ? 'user-a' : 'user-b'
                                });
                            }

                            Echo.private('chat.' + this.conversation.id)
                                .listen('MessageSent', (e) => {
                                    this.messages.push({
                                        id: this.messages[this.messages.length - 1].id + 1,
                                        text: e.conversationMessage.body,
                                        user: 'user-a'
                                    });
                                });
                            this.listening = true;
                        });
                    }.bind(this));
                } else {
                    axios.post('/conversations', {
                        message: message.text,
                        sender_id: this.userAId,
                        recipient_id: this.userBId
                    }).then(function (response) {
                        console.log('Message sent');
                    }).catch(function (error) {
                        console.log(error.response.status);
                    });
                }
            },
            
            clear: function (event) {
                $('#' + event.currentTarget.id).closest('.PrivateChatItem').remove();
                Vue.delete(this.$parent.conversationPartners, this.index);
                if (this.listening === true) {
                    Echo.leave('chat.' + this.conversation.id);
                }
            }
        }
    }
</script>