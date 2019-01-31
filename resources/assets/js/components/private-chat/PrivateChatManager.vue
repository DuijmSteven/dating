<template>
    <div class="PrivateChatManager">
        <div class="PrivateChatManager__head">
            <div class="PrivateChatManager__head__title">
                Conversations
            </div>
            <div class="PrivateChatManager__head__actionIcons">
                <div v-on:click="toggle"
                     id="PrivateChatManager__toggle"
                     class="PrivateChatManager__toggle"
                >
                    <i class="material-icons material-icon minimize">minimize</i>
                </div>
            </div>
        </div>
        <div id="PrivateChatManager__body" class="PrivateChatManager__body" data-simplebar data-simplebar-auto-hide="false">
            <div v-for="(conversation, index) in conversations"
                 v-on:click="$parent.addChat(conversation.currentUser.id, conversation.otherUser.id)"
                 class="PrivateChatManager__item"
            >
                <div class="PrivateChatManager__item__left">
                    <img class="PrivateChatManager__item__profilePicture"
                         :src="conversation.otherUser.profileImageUrl"
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
            };
        },

        created() {
            this.fetchUserConversations();
        },

        methods: {
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
                    console.log(error);
                });
            },

            toggle: function () {
                $('#PrivateChatManager__body').slideToggle('fast');
            }
        },
        mounted() {
            this.$root.$on('fetchUserConversations', () => {
                this.fetchUserConversations();
            });
        }
    }
</script>