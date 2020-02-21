<template>
    <div class="PrivateChatManager"
         v-if="fullyLoaded"
         v-bind:class="{
            'maximized': this.isMaximized,
            'minimized': !this.isMaximized,
            'PrivateChatManager--xs': $mq === 'xs',
            'PrivateChatManager--sm': $mq === 'sm',
            'PrivateChatManager--md': $mq === 'md',
            'PrivateChatManager--lg': $mq === 'lg'
        }">
        <div v-on:click="toggle"
            class="PrivateChatManager__head"
        >
            <div class="PrivateChatManager__head__title">
                <div
                    v-if="$parent.chatTranslations"
                    class="PrivateChatManager__head__title__text">
                    {{ $parent.chatTranslations['conversations'] }} ({{ conversations.length }})

                    <div
                        v-if="newMessagesExist"
                        class="PrivateChatManager__head__newMessages"
                    >
                        <i class="material-icons">
                            email
                        </i>
                    </div>
                </div>
            </div>
            <div class="PrivateChatManager__head__actionIcons">
                <div id="PrivateChatManager__toggle"
                     class="PrivateChatManager__toggle"
                >
                    <i class="PrivateChatManager__toggle__icon--minimize material-icons"
                    >
                        minimize
                    </i>
                    <i class="PrivateChatManager__toggle__icon--message material-icons"
                    >
                        message
                    </i>

                    <div
                        v-if="newMessagesExist"
                        class="PrivateChatManager__head__newMessages mobile"
                    >
                        <i class="material-icons">
                            email
                        </i>
                    </div>
                </div>
            </div>
        </div>
        <div id="PrivateChatManager__body"
             class="PrivateChatManager__body"
        >
            <div class="force-overflow"></div>

            <div
                v-if="conversations.length === 0"
                class="PrivateChatManager__body__empty"
            >
                {{ this.$parent.chatTranslations['no_conversations'] }}
            </div>

            <div v-for="(conversation, index) in conversations" :key="'A' + index"
                 v-on:click="clickedOnConversationItem(conversation, index)"
                 class="PrivateChatManager__item"
                 v-bind:class="{isNewOrHasNewMessage: conversation.newActivity}"
                 :id="'PrivateChatManager__item--' + index"
            >
                <div class="PrivateChatManager__item__left">
                    <div class="PrivateChatManager__item__profilePicture__secondWrapper">
                        <div class="PrivateChatManager__item__profilePicture__wrapper">
                            <img class="PrivateChatManager__item__profilePicture"
                                 :src="profileImageUrl(conversation.otherUserId, conversation.otherUserProfileImage, conversation.otherUserGender)"
                                 alt="profile-image"
                            >
                        </div>

                        <div
                            v-if="$parent.onlineUserIds && $parent.onlineUserIds.includes(conversation.otherUserId)"
                            class="PrivateChatManager__item__profilePicture__secondWrapper__onlineCircle"
                        ></div>
                    </div>
                </div>
                <div class="PrivateChatManager__item__right">
                    <div class="PrivateChatManager__item__right__topPart">
                        <div class="PrivateChatManager__item__userName">
                            {{ conversation.otherUserUsername }}
                        </div>
                        <span class="PrivateChatManager__item__date">{{ conversation.conversation_updated_at | moment("add", "1 hours") | formatDate }}</span>
                    </div>
                    <div class="PrivateChatManager__item__lastMessage">
                        {{ conversation.last_message_body ? conversation.last_message_body : '&nbsp;' }}
                        <i class="material-icons attachmentIcon" v-if="conversation.last_message_has_attachment">attachment</i>
                    </div>
                </div>

                <i
                    class="material-icons material-icon PrivateChatManager__item__deleteIcon"
                    v-on:click="confirmDeleteConversation(conversation.id)"
                    @click.stop="$event.stopPropagation()"
                >clear</i>

                <i
                    class="material-icons material-icon PrivateChatManager__item__newMessagesIcon"
                >email</i>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
        ],

        data() {
            return {
                conversations: [],
                currentUser: undefined,
                isMaximized: true,
                fullyLoaded: false,
                countConversationsWithNewMessages: 0,
                newMessagesExist: false
            };
        },

        created() {
            this.fetchUserConversations(true);

            setInterval(() => {
                this.fetchUserConversations();
            }, 10000);
        },

        methods: {
            profileImageUrl(userId, $filename, $gender, $thumbnail = true) {
                if (!$filename) {
                    if ($gender === 'male') {
                        return DP.malePlaceholderImageUrl;
                    } else {
                        return DP.femalePlaceholderImageUrl;
                    }
                }

                if ($thumbnail) {
                    let splitFilename = $filename.split('.');
                    let filename = splitFilename[0];
                    let extension = splitFilename[1];

                    let thumbFilename = filename + '_thumb' + '.' + extension;

                    return DP.usersCloudPath + '/' + userId + '/images/' + thumbFilename;
                }

                return DP.usersCloudPath + '/' + userId + '/images/' + $filename
            },
            lastMessageOfConversationHasAttachment: function(conversation) {
                return conversation.messages[conversation.messages.length -1].attachment != null;
            },
            clickedOnConversationItem: function (conversation, itemIndex) {
                this.$parent.addChat(conversation.currentUserId, conversation.otherUserId, '1', true);
                this.$parent.setConversationActivityForUser(conversation, 0);
                this.removeIsNewOrHasNewMessageClass(itemIndex);
            },
            removeIsNewOrHasNewMessageClass: function (index) {
                if ($('#PrivateChatManager__item--' + index).hasClass('isNewOrHasNewMessage') && this.countConversationsWithNewMessages === 1) {
                    this.newMessagesExist = false;
                }

                $('#PrivateChatManager__item--' + index).removeClass('isNewOrHasNewMessage');
            },
            confirmDeleteConversation: function (conversationId) {
                this.$dialog.confirm({
                    title: this.$parent.chatTranslations['delete_conversation'],
                    body: this.$parent.chatTranslations['delete_conversation_confirm']
                }, {
                    customClass: 'ConfirmDialog',
                    okText: this.$parent.chatTranslations['yes'],
                    cancelText: this.$parent.chatTranslations['no']
                })
                    .then(() => {
                        this.deleteConversation(conversationId);
                    }).catch(() => {
                    });
            },
            deleteConversation: function (conversationId) {
                this.$root.$emit('conversationDeleted', conversationId);

                axios.delete('/api/conversations/' + conversationId).then(response => {
                    this.fetchUserConversations();

                    this.eventHub.$emit('conversationDeleted', conversationId);
                }).catch(function (error) {
                });
            },
            fetchConversationManagerStatus: function () {
                axios.get('/api/conversations/conversation-manager-state/' + parseInt(DP.authenticatedUser.id)).then(
                    response => {
                        this.conversationManagerState = response;

                        if (!(this.$mq === 'xs' || this.$mq === 'sm')) {
                            this.isMaximized = this.conversationManagerState.data === 1;

                            if (this.isMaximized) {
                                $('.PrivateChatManager').addClass('maximized');
                            }
                        }

                        this.fullyLoaded = true;
                    }
                );
            },

            fetchUserConversations: function (fetchStatus) {
                axios.get('/api/conversations/' + this.user.id).then(response => {
                    this.conversations = response.data;

                    this.countConversationsWithNewMessages = 0;

                    this.conversations.map(conversation => {
                        if (conversation.user_a_id === this.user.id) {
                            conversation.otherUserId = conversation.user_b_id;
                            conversation.currentUserId = conversation.user_a_id;
                            conversation.currentUserProfileImage = conversation.user_a_profile_image_filename;
                            conversation.otherUserProfileImage = conversation.user_b_profile_image_filename;
                            conversation.currentUserUsername = conversation.user_a_username;
                            conversation.otherUserUsername = conversation.user_b_username;
                            conversation.currentUserGender = conversation.user_a_gender;
                            conversation.otherUserGender = conversation.user_b_gender;

                            if (conversation.conversation_new_activity_for_user_a) {
                                conversation.newActivity = true;

                                this.countConversationsWithNewMessages++;
                            } else {
                                conversation.newActivity = false;
                            }
                        } else {
                            conversation.currentUserId = conversation.user_b_id;
                            conversation.otherUserId = conversation.user_a_id;
                            conversation.currentUserUsername = conversation.user_b_profile_image_filename;
                            conversation.otherUserProfileImage = conversation.user_a_profile_image_filename;
                            conversation.currentUserUsername = conversation.user_b_username;
                            conversation.otherUserUsername = conversation.user_a_username;
                            conversation.currentUserGender = conversation.user_b_gender;
                            conversation.otherUserGender = conversation.user_a_gender;

                            if (conversation.conversation_new_activity_for_user_b) {
                                conversation.newActivity = true;

                                this.countConversationsWithNewMessages++;
                            } else {
                                conversation.newActivity = false;
                            }
                        }

                        if (this.countConversationsWithNewMessages > 0) {
                            this.newMessagesExist = true;
                        } else {
                            this.newMessagesExist = false;
                        }
                    });

                    if (fetchStatus) {
                        this.fetchConversationManagerStatus();
                    }
                }).catch(function (error) {
                });
            },
            toggle: function () {
                $('#PrivateChatManager__body').slideToggle('fast');
                this.isMaximized = !this.isMaximized;

                if (['xs', 'sm'].includes(this.$mq) && this.isMaximized) {
                    $('body').css('overflow-y', 'hidden');
                } else {
                    $('body').css('overflow-y', 'scroll');
                }

                let managerState = this.isMaximized ? 1 : 0;

                axios.get(
                    '/api/conversations/conversation-manager-state/' +
                    parseInt(DP.authenticatedUser.id) + '/' +
                    managerState
                ).then(
                    response => {}
                );
            }
        },
        mounted() {
            this.$root.$on('fetchUserConversations', () => {
                this.fetchUserConversations(true);
            });

            if (this.$mq === 'xs' || this.$mq === 'sm') {
                console.log('mobile');
                $('#PrivateChatManager__body').slideToggle('fast');
                this.isMaximized = !this.isMaximized;

                if (['xs', 'sm'].includes(this.$mq) && this.isMaximized) {
                    $('body').css('overflow-y', 'hidden');
                } else {
                    $('body').css('overflow-y', 'scroll');
                }

            }
        }
    }
</script>