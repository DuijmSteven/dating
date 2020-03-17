<template>
    <div
        @mouseenter="preventWindowScroll()"
        @mouseleave="allowWindowScroll()"
        :class="'PrivateChatItem PrivateChatItem--' + index + ' ' + statusClass"
    >
        <div :id="'PrivateChatItem__head--' + index"
             class="PrivateChatItem__head"
        >
            <div class="PrivateChatItem__head__wrapper">
                <div class="PrivateChatItem__user">
                    <div class="PrivateChatItem__profilePicture__wrapper">
                        <img class="PrivateChatItem__profilePicture"
                             :src="partner.profileImageUrlThumb">
                    </div>

                    <div class="PrivateChatItem__username">
                        <a class="PrivateChatItem__usernameAnchor"
                           :href="singleProfileUrl + partner.username">
                            {{ partner.username }}
                        </a>
                    </div>

                    <div
                        v-if="$parent.onlineUserIds && $parent.onlineUserIds.includes(partner.id)"
                        class="PrivateChatItem__user__onlineCircle"
                    ></div>
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
                    <div v-on:click="clear(partner.id)"
                         :id="'PrivateChatItem__clear--' + index"
                         class="PrivateChatItem__clear"
                    >
                        <i class="material-icons material-icon clear">clear</i>
                    </div>
                </div>
            </div>

<!--            <div class="PrivateChatItem__head__onlineBar"></div>-->
        </div>

        <div :id="'PrivateChatItem__body--' + index" class="PrivateChatItem__body">
            <div class="PrivateChatItem__body__wrapper">
                <div
                    v-if="showNoCredits"
                    class="PrivateChatItem__feedback PrivateChatItem__feedback--notEnoughCredits">
                    <div>{{ this.$parent.chatTranslations['not_enough_credits'] }}</div>
                    <div>
                        <a href=""
                            v-on:click="minimizeAllChatActivity($event)"
                        >
                            {{ this.$parent.chatTranslations['refill'] }}
                        </a>
                    </div>
                </div>

                <div
                    v-if="errorMessages.length > 0"
                    class="PrivateChatItem__feedback PrivateChatItem__feedback--error">

                    <div
                        v-for="(errorMessage, index) in errorMessages"
                    >{{ errorMessage }}</div>
                </div>

                <div :id="'PrivateChatItem__body__content--' + index"
                     class="PrivateChatItem__body__content"
                >
                    <div
                        v-if="conversation &&
                            (displayedMessages.length >= messagesPerRequest) &&
                            !fetchingOlderMessages &&
                            !allMessagesFetched &&
                            waitedAfterLoaderDisappeared"
                        class="fetchMoreButton"
                        v-on:click="fetchOlderMessages()"
                    >
                        {{ this.$parent.chatTranslations['older_messages'] }}
                        <i class="material-icons">
                            get_app
                        </i>
                    </div>

                    <div
                        v-if="fetchingOlderMessages || fetchingInitial"
                        class="fetchingMessages"
                    >
                        <div class="loader"></div>
                    </div>

                    <div
                        v-if="allMessagesFetched && (displayedMessages.length >= messagesPerRequest)"
                        class="allMessagesFetched"
                    >
                        {{ this.$parent.chatTranslations['no_more_messages'] }}
                    </div>

                    <div
                        v-if="allMessagesFetched && displayedMessages.length === 0"
                        class="allMessagesFetched"
                    >
                        {{ this.$parent.chatTranslations['no_messages_yet'] }}
                    </div>

                    <chat-message
                        v-for="(message, index) in displayedMessages"
                        :message="message"
                        :key="message.id + '\'' + index + '\''"
                        :conversation="conversation"
                    ></chat-message>
                </div>
                <chat-form
                    v-on:message-sent="addMessage"
                    v-on:show-no-credits="promptToBuyCredits"
                    :user="user"
                    :index="index"
                    :conversation="conversation"
                    :sendingMessage="sendingMessage"
                    :chatTranslations="this.$parent.chatTranslations"
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
                checkingScroll: false,
                windowScrollPrevented: false,
                offset: 0,
                messagesPerRequest: 5,
                fetchingOlderMessages: false,
                allMessagesFetched: false,
                fetchingInitial: true,
                waitedAfterLoaderDisappeared: false,
                timeToWaitAfterLoaderDisappears: 20,
                sendingMessage: false,
                showNoCredits: false,
                creditsUrl: DP.creditsUrl,
                singleProfileUrl: DP.singleProfileUrl,
                errorMessages: [],
                checkingForNewAndShowing: false
            };
        },

        created() {
            this.statusClass = this.partner.chatState === '1' ? 'maximized' : 'minimized';
            this.isMaximized = this.partner.chatState === '1';

            this.fetchMessagesAndListenToChannel();

            this.$root.$on('conversationDeleted', conversationId => {
                if (conversationId === this.conversation.id) {
                    this.clear(this.partner.id);
                }
            });
        },

        methods: {
            fetchOlderMessages() {
                if (!this.allMessagesFetched && !this.fetchingOlderMessages) {

                    this.waitedAfterLoaderDisappeared = false;
                    this.fetchingOlderMessages = true;

                    axios.get('/api/conversations/' + this.user.id + '/' + this.partner.id + '/' + this.offset + '/' + this.messagesPerRequest).then(response => {
                        this.conversation = response.data;

                        let messages = this.conversation.messages;

                        if (messages.length > 0) {
                            this.offset += this.messagesPerRequest;
                            this.addMessagesToBeDisplayed(messages, true);
                        } else {
                            this.allMessagesFetched = true;
                        }

                        this.fetchingOlderMessages = false;

                        setTimeout(() => {
                            this.waitedAfterLoaderDisappeared = true;
                        }, this.timeToWaitAfterLoaderDisappears);
                    });

                }
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
                    if (this.currentHighestMessageId !== undefined && !this.checkingForNewAndShowing) {
                        this.checkForNewMessagesAndShowThem();
                    }
                }, 10000);
            },

            checkForNewMessagesAndShowThem() {
                this.checkingForNewAndShowing = true;

                axios.get('/api/conversation-messages/' + this.user.id + '/' + this.partner.id + '/' + this.currentHighestMessageId).then(response => {
                    let messages = response.data;

                    if (messages.length > 0) {
                        this.addMessagesToBeDisplayed(messages);

                        let newActivity = false;

                        messages.forEach(message => {
                            if (message.sender.id !== this.user.id) {
                                newActivity = true;
                            }
                        });

                        if (newActivity) {
                            this.setNewActivity();
                        }
                    } else {
                        this.checkingForNewAndShowing = false;
                    }
                });
            },

            fetchMessagesAndPopulate() {
                axios.get('/api/conversations/' + this.user.id + '/' + this.partner.id + '/' + this.offset + '/' + this.messagesPerRequest).then(response => {
                    this.conversation = response.data;

                    if (this.conversation.messages.length > 0) {
                        this.offset += this.messagesPerRequest;

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

                        if (this.conversation.messages.length > 0) {
                            this.addMessagesToBeDisplayed(this.conversation.messages);
                        }

                        if (
                            this.conversation.newActivity
                        ) {
                            this.setNewActivity();
                        }
                    }

                    this.fetchingInitial = false;

                    setTimeout(() => {
                        this.waitedAfterLoaderDisappeared = true;
                    }, this.timeToWaitAfterLoaderDisappears);
                }).catch((error) => {
                    this.fetchingInitial = false;

                    if (error.response.status === 404) {
                        this.allMessagesFetched = true;
                    }

                    setTimeout(() => {
                        this.waitedAfterLoaderDisappeared = true;
                    }, this.timeToWaitAfterLoaderDisappears);
                });
            },

            setNewActivity: function () {
                $('#PrivateChatItem__head--' + this.index).addClass('PrivateChatItem__head__notify');
            },

            addMessagesToBeDisplayed: function (messages, addToTop = false) {
                if (!addToTop) {
                    let messageIds = messages.map(message => {
                        return message.id;
                    });

                    this.currentHighestMessageId = Math.max.apply(null, messageIds);

                    messages.reverse().forEach(message => {
                        this.displayedMessages.push(this.buildMessageObject(message));
                    });

                    setTimeout(() => {
                        this.scrollChatToBottom();
                    }, 200);
                } else {
                    messages.forEach(message => {
                        this.displayedMessages.unshift(this.buildMessageObject(message));
                    });
                }

                this.checkingForNewAndShowing = false;
            },

            buildMessageObject: function (message) {
                return {
                    id: message.id,
                    text: message.body,
                    attachment: message.attachment,
                    user: message.sender.id === this.user.id ? 'user-b' : 'user-a',
                    createdAt: message.created_at
                };
            },

            setConversationActivityForUserFalse: function () {
                if ($('#PrivateChatItem__head--' + this.index).hasClass('PrivateChatItem__head__notify')) {
                    $('#PrivateChatItem__head--' + this.index).removeClass('PrivateChatItem__head__notify');
                    axios.get('/api/conversations/set-conversation-activity-for-user/' + this.user.id + '/' + this.partner.id + '/' + this.user.id + '/' + '0').then(
                        response => {}
                    );
                }
            },

            promptToBuyCredits() {
                this.showNoCredits = true;
                setTimeout(() => {
                    this.showNoCredits = false;
                }, 4000)
            },
            addMessage(message) {
                this.sendingMessage = true;
                this.setConversationActivityForUserFalse();

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
                    this.sendingMessage = false;

                    if (this.currentHighestMessageId !== undefined) {
                        this.checkForNewMessagesAndShowThem();
                    } else {
                        this.fetchMessagesAndPopulate();
                    }

                    this.$root.$emit('updateUserCredits');
                    this.$root.$emit('messageSent');

                    this.fetchUserConversations();
                }).catch((error) => {
                    this.$root.$emit('errorMessageSent');

                    if (error && error.response.status === 403 && error.response.data === 'Not enough credits') {
                        this.showNoCredits = true;

                        setTimeout(() => {
                            this.showNoCredits = false;
                        }, 4000)
                    } else if (error && error.response) {
                        if (error.response.data && error.response.data.errors) {
                            $.each(error.response.data.errors, (key, error) => {
                                $.each(error, (key, error) => {
                                    this.errorMessages.push(error);
                                });
                            });

                            setTimeout(() => {
                                this.errorMessages = [];
                            }, 3000);
                        } else {
                            this.errorMessages.push(this.$parent.chatTranslations['general_error']);

                            setTimeout(() => {
                                this.errorMessages = [];
                            }, 3000)
                        }
                    } else {
                        this.errorMessages.push(this.$parent.chatTranslations['general_error']);

                        setTimeout(() => {
                            this.errorMessages = [];
                        }, 3000)
                    }

                    this.sendingMessage = false;
                });
            },

            clear(partnerId) {
                this.setConversationActivityForUserFalse();

                Vue.delete(this.$parent.conversationPartners, this.index);
                $('.PrivateChatItem--' + this.index).remove();

                axios.get(
                    '/api/conversations/conversation-partner-ids/remove/' +
                    parseInt(DP.authenticatedUser.id) +
                    '/' +
                    parseInt(partnerId)
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
            minimizeAllChatActivity($event) {
                $event.preventDefault();

                if (['xs'].includes(this.$mq)) {
                    axios.get(
                        '/api/conversations/conversation-partner-ids/remove/' +
                        parseInt(DP.authenticatedUser.id) +
                        '/' +
                        parseInt(this.partner.id)
                    ).then(
                        response => {
                        }
                    );

                    axios.get(
                        '/api/conversations/conversation-manager-state/' +
                        parseInt(DP.authenticatedUser.id) + '/' +
                        '0'
                    ).then(
                        response => {
                            axios.get('/api/conversations/set-conversation-activity-for-user/' + this.user.id + '/' + this.partner.id + '/' + this.user.id + '/' + '0').then(
                                response => {
                                    window.location = this.creditsUrl;
                                }
                            );
                        }
                    );
                } else {
                    window.location = this.creditsUrl;

                }
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