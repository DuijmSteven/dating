<template>
    <div
            @mouseenter="preventWindowScroll()"
            @mouseleave="allowWindowScroll()"
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
                    <div v-on:click="maximize"
                         :id="'PrivateChatItem__maximize--' + index"
                         class="PrivateChatItem__maximize"

                    >
                        <i class="material-icons material-icon maximize">fullscreen</i>
                    </div>
                    <div v-on:click="resetMaximize"
                         :id="'PrivateChatItem__resetMaximize--' + index"
                         class="PrivateChatItem__resetMaximize"

                    >
                        <i class="material-icons material-icon resetMaximize">fullscreen_exit</i>
                    </div>
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
                     @scroll="checkScrollTop()"
                >
                    <chat-message
                            v-for="(message, index) in displayedMessages"
                            :message="message"
                            :key="message.id"
                            :conversation="conversation"
                    ></chat-message>
                </div>
                <chat-form
                        v-on:message-sent="addMessage"
                        :user="user"
                        :index="index"
                        :conversation="conversation"
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
                allMessages: undefined,
                displayedMessages: [],
                conversation: undefined,
                highestConversationId: undefined,
                isMaximized: true,
                statusClass: 'maximized',
                previousHighestMessageId: undefined,
                currentHighestMessageId: undefined,
                firstIteration: true,
                intervalToFetchMessages: undefined,
                scrollTop: undefined,
                messagesPerScroll: 6,
                checkingScroll: false,
                windowScrollPrevented: false
            };
        },

        created() {
            this.statusClass = this.partner.chatState === '1' ? 'maximized' : 'minimized';
            this.isMaximized = this.partner.chatState === '1';

            this.fetchMessagesAndListenToChannel();
        },

        updated() {
        },

        methods: {
            checkScrollTop() {
                let scrollTop = $('#PrivateChatItem__body__content--' + this.index).scrollTop();

                if (!this.justCheckedScrollTop && scrollTop < 20 && this.allMessages.length > 0) {
                    let latestMessage;
                    this.justCheckedScrollTop = true;

                    let messagesAmountToLoad;
                    if (this.allMessages.length >= this.messagesPerScroll) {
                        messagesAmountToLoad = this.messagesPerScroll;
                    } else {
                        messagesAmountToLoad = this.allMessages.length;
                    }

                    this.allMessages.splice(-messagesAmountToLoad, messagesAmountToLoad).reverse().forEach(message => {
                        latestMessage = {
                            id: message.id,
                            text: message.body,
                            attachment: message.attachment,
                            user: message.sender.id === this.user.id ? 'user-a' : 'user-b',
                            createdAt: message.created_at
                        };

                        this.displayedMessages.splice(0, 0, latestMessage);
                    });
                }

                setTimeout(() => {
                    this.justCheckedScrollTop = false;
                }, 200);
            },
            preventWindowScroll() {
                if (this.windowHasScrollbar()) {
                    this.scrollTop = $(document).scrollTop();

                    let $body = $('body');

                    if (['xs', 'sm'].includes(this.$mq)) {
                        $body.css('overflow-y', 'hidden');
                    } else {
                        $body.css('overflow-y', 'scroll');
                    }

                    $body.css('top', -this.scrollTop);
                    $body.css('position', 'fixed');
                    $body.css('overflow-y', 'scroll');

                    this.windowScrollPrevented = true;
                }
            },

            allowWindowScroll() {
                if (this.windowScrollPrevented) {
                    this.resetBrowserScrollPosition();
                    this.windowScrollPrevented = false;
                }
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

            windowHasScrollbar() {
                return document.documentElement.scrollHeight > $(window).height();
            },

            fetchMessagesAndListenToChannel() {
                this.fetchMessagesAndPopulate();

                this.intervalToFetchMessages = setInterval(() => {
                    this.fetchMessagesAndPopulate();
                }, 10000);
            },

            fetchMessagesAndPopulate() {
                let latestMessage;

                axios.get('/api/conversations/' + this.user.id + '/' + this.partner.id).then(response => {
                    this.conversation = response.data;

                    if (this.conversation.messages.length > 0) {
                        if (this.user.id === this.conversation.user_a_id) {
                            if (this.conversation.new_activity_for_user_a) {
                                this.conversation.newActivity = true;
                            } else {
                                this.conversation.newActivity = false;
                            }
                        } else {
                            if (this.conversation.new_activity_for_user_b) {
                                this.conversation.newActivity = true;
                            } else {
                                this.conversation.newActivity = false;
                            }
                        }

                        this.currentHighestMessageId = this.conversation.messages[this.conversation.messages.length - 1].id;

                        if (this.previousHighestMessageId === undefined || this.previousHighestMessageId !== this.currentHighestMessageId) {

                            if (this.previousHighestMessageId === undefined) {
                                this.displayedMessages = [];
                                this.allMessages = this.conversation.messages;

                                this.allMessages.splice(-this.messagesPerScroll, this.messagesPerScroll).forEach(message => {
                                    latestMessage = {
                                        id: message.id,
                                        text: message.body,
                                        attachment: message.attachment,
                                        user: message.sender.id === this.user.id ? 'user-a' : 'user-b',
                                        createdAt: message.created_at
                                    };

                                    this.displayedMessages.push(latestMessage);
                                });

                            } else {
                                this.conversation.messages.forEach(message => {
                                    if (message.id > this.previousHighestMessageId) {
                                        latestMessage = {
                                            id: message.id,
                                            text: message.body,
                                            attachment: message.attachment,
                                            user: message.sender.id === this.user.id ? 'user-a' : 'user-b',
                                            createdAt: message.created_at
                                        };

                                        this.displayedMessages.push(latestMessage);
                                    }
                                });
                            }

                            setTimeout(() => {
                                this.scrollChatToBottom();
                            }, 200);

                            if (
                                this.conversation.newActivity
                            ) {
                                $('#PrivateChatItem__head--' + this.index).addClass('PrivateChatItem__head__notify');
                            }
                        }

                        this.previousHighestMessageId = this.currentHighestMessageId;
                    }
                }).catch((error) => {
                });
            },
            setConversationActivityForUserFalse: function () {
                if ($('#PrivateChatItem__head--' + this.index).hasClass('PrivateChatItem__head__notify')) {
                    $('#PrivateChatItem__head--' + this.index).removeClass('PrivateChatItem__head__notify');
                    axios.get('/api/conversations/set-conversation-activity-for-user/' + this.user.id + '/' + this.partner.id + '/' + this.user.id + '/' + '0').then(
                        response => {
                            console.log(response);
                        }
                    );
                }
            },
            addMessage(message) {
                this.setConversationActivityForUserFalse();

                let newMessage = {
                    text: message.text,
                    attachment: message.attachment,
                    user: 'user-a'
                };

                setTimeout(() => {
                    this.scrollChatToBottom();
                }, 50);

                let data = new FormData();
                data.append('message', message.text);
                data.append('sender_id', this.user.id);
                data.append('recipient_id', this.partner.id);

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
                this.setConversationActivityForUserFalse();

                Vue.delete(this.$parent.conversationPartners, this.index);
                $('.PrivateChatItem--' + this.index).remove();

                axios.get(
                    '/api/conversations/conversation-partner-ids/remove/' +
                    parseInt(DP.authenticatedUser.id) +
                    '/' +
                    parseInt(partner.id)
                ).then(
                    response => {
                    }
                );

                this.resetBrowserScrollPosition();

                clearInterval(this.intervalToFetchMessages);
            },

            toggle() {
                this.setConversationActivityForUserFalse();

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
                    parseInt(this.partner.id) +
                    '/' +
                    chatState
                ).then(
                    response => {
                    }
                );
            },

            maximize() {
                this.setConversationActivityForUserFalse();

                $('#PrivateChatItem__body--' + this.index).css('display', 'block');
                $('.PrivateChatItem--' + this.index).removeClass('minimized');
                $('.PrivateChatItem--' + this.index).addClass('fullScreen');
            },

            resetMaximize() {
                this.setConversationActivityForUserFalse();

                $('.PrivateChatItem--' + this.index).removeClass('fullScreen');
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