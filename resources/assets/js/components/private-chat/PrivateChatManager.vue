<template>
    <div class="PrivateChatManager" v-bind:class="{
                    'maximized': this.isMaximized,
                    'minimized': !this.isMaximized,
                    'PrivateChatManager--xs': $mq === 'xs',
                    'PrivateChatManager--sm': $mq === 'sm',
                    'PrivateChatManager--md': $mq === 'md',
                    'PrivateChatManager--lg': $mq === 'lg'
                }">
        <div class="PrivateChatManager__head">
            <div class="PrivateChatManager__head__title">
                <span class="PrivateChatManager__head__title__text">Conversations</span>
            </div>
            <div class="PrivateChatManager__head__actionIcons">
                <div v-on:click="toggle"
                     id="PrivateChatManager__toggle"
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

            <div v-for="(conversation, index) in conversations"
                 v-on:click="$parent.addChat(conversation.currentUser.id, conversation.otherUser.id)"
                 class="PrivateChatManager__item"
            >
                <div class="PrivateChatManager__item__left">
                    <img class="PrivateChatManager__item__profilePicture"
                         :src="conversation.otherUser.profileImageUrl"
                         alt="profile-image"
                    >
                </div>
                <div class="PrivateChatManager__item__right">
                    <div class="PrivateChatManager__item__right__topPart">
                        <span class="PrivateChatManager__item__userName">{{ conversation.otherUser.username }}</span>
                        <span class="PrivateChatManager__item__date">{{ conversation.updatedAtHumanReadable }}</span>
                    </div>
                    <div class="PrivateChatManager__item__lastMessage">
                        {{ conversation.messages[conversation.messages.length -1].type === 'generic' ?
                           conversation.messages[conversation.messages.length -1].body :
                           'flirt'
                        }}
                    </div>
                </div>
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
            this.fetchConversationManagerStatus();
        },

        methods: {
            fetchConversationManagerStatus: function () {
                axios.get('/api/conversations/conversation-manager-state/' + parseInt(DP.authenticatedUser.id)).then(
                    response => {
                        this.conversationManagerState = response;

                        this.isMaximized = this.conversationManagerState.data === '1';

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
                        if (conversation.user_a.id === this.user.id) {
                            conversation.otherUser = conversation.user_b;
                            conversation.currentUser = conversation.user_b;
                        } else {
                            conversation.currentUser = conversation.user_a;
                            conversation.otherUser = conversation.user_b;
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
        }
    }
</script>