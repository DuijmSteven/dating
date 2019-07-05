<template>
    <div
        @mouseenter="mouseOver()"
        @mouseleave="mouseLeave()"
        :class="'PrivateChatItem PrivateChatItem--' + index + ' ' + statusClass"
    >
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
                        :conversation="conversation"
                    ></chat-message>
                </div>
                <chat-form
                    v-on:message-sent="addMessage"
                    :user="user"
                    :index="index"
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
            'index',
            'storagePath',
        ],

        data() {
            return {
                listening: false,
                messages: [],
                conversation: undefined,
                userAId: undefined,
                userBId: undefined,
                highestConversationId: undefined,
                isMaximized: true,
                statusClass: 'maximized',
                previousHighestMessageId: undefined,
                currentHighestMessageId: undefined,
                firstIteration: true,
                intervalToFetchMessages: undefined,
                scrollTop: undefined
            };
        },

        created() {
            this.statusClass = this.partner.chatState === '1' ? 'maximized' : 'minimized';
            this.isMaximized = this.partner.chatState === '1';

            this.userAId = this.user.id;
            this.userBId = this.partner.id;

            this.fetchMessagesAndListenToChannel();
        },

        updated() {
            this.scrollChatToBottom();
        },

        methods: {
            mouseOver() {
                this.scrollTop = $(document).scrollTop();

                let $body = $('body');

                if (['xs', 'sm'].includes(this.$mq)) {
                    $body.css('overflow-y', 'hidden');
                } else {
                    $body.css('overflow-y', 'scroll');
                }

                $body.css('top', - this.scrollTop);
                $body.css('position', 'fixed');
                $body.css('overflow-y', 'scroll');
            },

            mouseLeave() {
                this.resetBrowserScrollPosition();
            },

            resetBrowserScrollPosition() {
                let $body = $('body');

                if (['xs', 'sm'].includes(this.$mq)) {
                    $body.css('overflow-y', 'hidden');
                } else {
                    $body.css('overflow-y', 'scroll');
                }

                $body.css('position', 'static');
                $body.css('overflow-y', 'auto');
                $(window).scrollTop(this.scrollTop);

                this.scrollTop = undefined;
            },

            fetchMessagesAndListenToChannel() {
                this.fetchMessagesAndPopulate();

                this.intervalToFetchMessages = setInterval(() => {
                    this.fetchMessagesAndPopulate();
                }, 5000);
            },

            fetchMessagesAndPopulate() {
                axios.get('/api/conversations/' + this.userAId + '/' + this.userBId).then(response => {
                    this.conversation = response.data;

                    if (this.conversation.messages.length > 0) {
                        this.currentHighestMessageId = this.conversation.messages[this.conversation.messages.length - 1].id;

                        if (this.previousHighestMessageId === undefined || this.previousHighestMessageId !== this.currentHighestMessageId) {
                            this.messages = [];

                            this.conversation.messages.forEach(message => {
                                this.messages.push({
                                    id: message.id,
                                    text: message.body,
                                    attachment: message.attachment,
                                    user: message.sender.id === this.user.id ? 'user-a' : 'user-b',
                                    createdAt: message.createdAtHumanReadable
                                });
                            });
                            this.previousHighestMessageId = this.currentHighestMessageId;

                            if (!firstIteration &&
                                !$('#PrivateChatItem__head--' + this.index)
                                    .hasClass('PrivateChatItem__head__notify')
                            ) {
                                $('#PrivateChatItem__head--' + this.index).addClass('PrivateChatItem__head__notify');
                            }
                        }

                        if (firstIteration) {
                            this.userBId = this.user.id === this.conversation.messages[0].sender_id ?
                                this.conversation.messages[0].recipient_id :
                                this.conversation.messages[0].sender_id;

                            this.userAId = this.user.id !== this.conversation.messages[0].sender_id ?
                                this.conversation.messages[0].recipient_id :
                                this.conversation.messages[0].sender_id;

                        }

                        this.firstIteration = false;
                    }
                }).catch((error) => {
                });
            },

            addMessage(message) {
                let data = new FormData();
                data.append('message', message.text);
                data.append('sender_id', this.userAId);
                data.append('recipient_id', this.userBId);

                if (message.attachment != null) {
                    data.append('attachment', message.attachment);
                }

                const config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                };

                axios.post('/conversations', data, config).then(() => {
                    this.fetchMessagesAndPopulate();
                    this.fetchUserConversations();
                }).catch((error) => {
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
                    response => {}
                );

                this.resetBrowserScrollPosition();

                clearInterval(this.intervalToFetchMessages);
            },

            toggle() {
                $('#PrivateChatItem__head--' + this.index).removeClass('PrivateChatItem__head__notify');
                $('#PrivateChatItem__body--' + this.index).slideToggle('fast');

                this.isMaximized = !this.isMaximized;

                if (['xs', 'sm'].includes(this.$mq) && this.isMaximized) {
                    $('body').css('overflow-y', 'hidden');
                } else {
                    $('body').css('overflow-y', 'scroll');
                }

                this.statusClass = this.isMaximized ? 'maximized' : 'minimized';

                let chatState = this.isMaximized ? '1' : '0';

                axios.get(
                    '/api/conversations/conversation-partner-ids/add/' +
                    parseInt(DP.authenticatedUser.id) +
                    '/' +
                    parseInt(this.userBId) +
                    '/' +
                    chatState
                ).then(
                    response => {}
                );
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