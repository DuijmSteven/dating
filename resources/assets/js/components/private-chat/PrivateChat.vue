<template>
    <div :class="'PrivateChatItem PrivateChatItem--' + index + ' ' + $mq">
        <div :id="'PrivateChatItem__head--' + index"
             class="PrivateChatItem__head">
            <div class="PrivateChatItem__head__wrapper">
                <div class="PrivateChatItem__user">
                    <img class="PrivateChatItem__profile-image"
                         :src="partner.profileImageUrl">
                    <div class="PrivateChatItem__username">{{ partner.username }}</div>
                </div>

                <div class="PrivateChatItem__actionIcons">
                    <div v-on:click="toggle"
                         :id="'PrivateChatItem__minimize--' + index"
                         class="PrivateChatItem__minimize"

                    >
                        <i class="material-icons material-icon minimize">minimize</i>
                    </div>
                    <div v-on:click="clear(partner)"
                         :id="'PrivateChatItem__clear--' + index"
                         class="PrivateChatItem__clear"
                    >
                        <i class="material-icons material-icon clear">clear</i>
                    </div>
                </div>
            </div>
        </div>

        <div :id="'PrivateChatItem__body--' + index" class="PrivateChatItem__body">
            <div class="PrivateChatItem__body__wrapper">
                <div :id="'PrivateChatItem__body__content--' + index"
                     class="PrivateChatItem__body__content"
                >
                    <chat-message
                            v-for="(message, index) in messages"
                            :message="message"
                            :key="message.id"
                    ></chat-message>
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
                userBId: undefined,
            };
        },

        created() {
            this.userAId = this.user.id;
            this.userBId = this.partner.id;

            this.fetchMessagesAndListenToChannel();
        },

        updated() {
            this.scrollChatToBottom();
        },

        methods: {
            fetchMessagesAndListenToChannel() {
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
                                user: e.user.id === this.user.id ? 'user-a' : 'user-b'
                            });
                            if (
                                !$('#PrivateChatItem__head--' + this.index)
                                    .hasClass('PrivateChatItem__head__notify')
                                &&
                                $('#PrivateChatItem__body--' + this.index).is(":hidden")
                            ) {
                                $('#PrivateChatItem__head--' + this.index).addClass('PrivateChatItem__head__notify');
                            }
                        });
                    this.listening = true;

                }).catch(function (error) {
                    console.log(error.response.status);
                });
            },

            addMessage(message) {
                axios.post('/conversations', {
                    message: message.text,
                    sender_id: this.userAId,
                    recipient_id: this.userBId
                }).then(() => {
                    this.fetchUserConversations();
                }).catch((error) => {
                    console.log(error);
                });
            },

            clear(partner) {
                Vue.delete(this.$parent.conversationPartners, this.index);
                $('.PrivateChatItem--' + this.index).remove();
                if (this.listening === true) {
                    Echo.leave('chat.' + this.conversation.id);
                    this.listening = false;
                }

                axios.get(
                    '/api/conversations/conversation-partner-ids/remove/' +
                    parseInt(DP.authenticatedUser.id) +
                    '/' +
                    parseInt(partner.id)
                ).then(
                    response => {
                        console.log(response);
                    }
                );
            },

            toggle() {
                $('#PrivateChatItem__head--' + this.index).removeClass('PrivateChatItem__head__notify');
                $('#PrivateChatItem__body--' + this.index).slideToggle('fast');
            },

            fetchUserConversations() {
                this.$root.$emit('fetchUserConversations');
            },

            scrollChatToBottom() {
                var objDiv = document.getElementById('PrivateChatItem__body__content--' + this.index);
                objDiv.scrollTop = objDiv.scrollHeight;
            }
        }
    }
</script>