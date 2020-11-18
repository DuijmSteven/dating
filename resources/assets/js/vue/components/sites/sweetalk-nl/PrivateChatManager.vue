<template>
    <div class="PrivateChatManager"
         v-bind:class="{
            'maximized': this.maximized,
            'minimized': !this.maximized,
            'PrivateChatManager--xs': $mq === 'xs',
            'PrivateChatManager--sm': $mq === 'sm',
            'PrivateChatManager--md': $mq === 'md',
            'PrivateChatManager--lg': $mq === 'lg'
        }">
        <div v-on:click="this.$parent.toggleManager"
            class="PrivateChatManager__head"
        >
            <div class="PrivateChatManager__head__title">
                <div
                    v-if="$parent.chatTranslations"
                    class="PrivateChatManager__head__title__text">
                    {{ $parent.chatTranslations['conversations'] }} ({{ conversations.length }})

                    <div
                        v-if="this.$parent.countConversationsWithNewMessages > 0"
                        class="PrivateChatManager__head__newMessages"
                    >
                        {{ this.$parent.countConversationsWithNewMessages }}
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
                        v-if="this.$parent.countConversationsWithNewMessages > 0"
                        class="PrivateChatManager__head__newMessages mobile"
                    >
                        {{ this.$parent.countConversationsWithNewMessages }}
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
                {{ this.$parent.chatTranslations ? this.$parent.chatTranslations['no_conversations'] : '' }}
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
                        <span class="PrivateChatManager__item__date">{{ conversation.conversation_updated_at | formatDate }}</span>
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
                >message</i>

            </div>
        </div>
    </div>
</template>

<script>
import { requestConfig } from '../../../common-imports';

    export default {
        props: [
            'user',
            'maximized',
            'conversations',
            'fetchingUserConversations',
        ],
        data() {
            return {
                currentUser: undefined,
            };
        },

        created() {
        },

        methods: {
            profileImageUrl(userId, $filename, $gender, $thumbnail = true) {
                if (!$filename) {
                    if ($gender === 1) {
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
                if ($('#PrivateChatManager__item--' + index).hasClass('isNewOrHasNewMessage') && this.$parent.countConversationsWithNewMessages === 1) {
                    //this.newMessagesExist = false;
                }

                $('#PrivateChatManager__item--' + index).removeClass('isNewOrHasNewMessage');
            },
            confirmDeleteConversation: function (conversationId) {
                this.$dialog.confirm({
                    title: this.$parent.chatTranslations['delete_conversation'],
                    body: this.$parent.chatTranslations['delete_conversation_confirm']
                }, {
                    customClass: 'ConfirmModal',
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

                axios.delete(
                    '/api/conversations/' + conversationId,
                    requestConfig
                ).then(response => {
                    this.fetchUserConversations();

                    this.eventHub.$emit('conversationDeleted', conversationId);
                }).catch(function (error) {
                });
            },

        },
        mounted() {
            // this.$root.$on('fetchUserConversations', () => {
            //     this.fetchUserConversations(true);
            // });

            if (this.$mq === 'xs' || this.$mq === 'sm') {
                $('#PrivateChatManager__body').slideToggle('fast');
                this.maximized = !this.maximized;

                if (['xs', 'sm'].includes(this.$mq) && this.maximized) {
                    $('body').css('overflow-y', 'hidden');
                } else {
                    $('body').css('overflow-y', 'scroll');
                }

            }
        }
    }
</script>