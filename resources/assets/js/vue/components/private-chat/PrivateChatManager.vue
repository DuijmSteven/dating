<template>
    <div class="PrivateChatManager"
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
                <span
                    v-if="$parent.chatTranslations"
                    class="PrivateChatManager__head__title__text">
                    {{ $parent.chatTranslations['conversations'] }} ({{ conversations.length }})
                </span>
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
                </div>
            </div>
        </div>
        <div id="PrivateChatManager__body"
             class="PrivateChatManager__body"
        >
            <div class="force-overflow"></div>

            <div v-for="(conversation, index) in conversations" :key="'A' + index"
                 v-on:click="clickedOnConversationItem(conversation, index)"
                 class="PrivateChatManager__item"
                 v-bind:class="{isNewOrHasNewMessage: conversation.newActivity}"
                 :id="'PrivateChatManager__item--' + index"
            >
                <div class="PrivateChatManager__item__left">
                    <div class="PrivateChatManager__item__profilePicture__secondWrapper">
                        <div class="PrivateChatManager__item__profilePicture__wrapper JS--roundImageWrapper">
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
                        {{ conversation.last_message_type === 'generic' ?
                           conversation.last_message_body :
                           'flirt'
                        }}
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
                isMaximized: true
            };
        },

        created() {
            this.fetchUserConversations();

            setInterval(() => {
                this.fetchUserConversations();
            }, 10000);

            this.fetchConversationManagerStatus();
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
                $('#PrivateChatManager__item--' + index).removeClass('isNewOrHasNewMessage');
            },
            confirmDeleteConversation: function (conversationId) {
                this.$dialog.confirm({
                    title: 'Delete conversation',
                    body: 'Are you sure you want to delete this conversation?'
                }, {
                    customClass: 'ConfirmDialog',
                    okText: 'Yes',
                    cancelText: 'No'
                })
                    .then(() => {
                        this.deleteConversation(conversationId);
                    }).catch(() => {
                    });
            },
            deleteConversation: function (conversationId) {
                axios.delete('/api/conversations/' + conversationId).then(response => {
                    this.fetchUserConversations();
                }).catch(function (error) {
                });
            },
            fetchConversationManagerStatus: function () {
                axios.get('/api/conversations/conversation-manager-state/' + parseInt(DP.authenticatedUser.id)).then(
                    response => {
                        this.conversationManagerState = response;

                        this.isMaximized = this.conversationManagerState.data === 1;

                        if (this.isMaximized) {
                            $('#PrivateChatManager__body').css('display', 'block');
                        } else {
                            $('#PrivateChatManager__body').css('display', 'none');
                        }
                    }
                );
            },

            fetchUserConversations: function () {
                axios.get('/api/conversations/' + this.user.id).then(response => {
                    this.conversations = response.data;

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
                            } else {
                                conversation.newActivity = false;
                            }
                        }
                    });
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
                this.fetchUserConversations();
            });

            $('.JS--roundImageWrapper').each((index, element) => {
                this.$parent.fitRoundImageToContainer($(element));
            });
        }
    }
</script>