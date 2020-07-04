<template>
    <div class="Tile PublicChat">
        <div class="labelNew">Nieuwe functie!</div>

        <div class="Tile__heading PublicChat__heading">
            <span class="material-icons">
                chat
            </span>

            {{ this.$parent.chatTranslations ? this.$parent.chatTranslations['public_chat'] : '' }}
        </div>

        <div class="Tile__body PublicChat__panel">
            <div
                v-bind:class="{'noMessages': displayedMessages.length === 0}"
                class="PublicChat__panelBody"
                id="PublicChat__panelBody"
            >
                <ul
                    v-if="displayedMessages.length > 0"
                    class="PublicChat__chat"
                >
                    <li
                        v-for="(item, index) in displayedMessages"
                        class="clearfix"
                    >
                        <div class="PublicChat__Img pull-left">
                            <a
                                v-if="item.sender.id !== this.DP.authenticatedUser.id"
                                :href="this.DP.singleProfileUrl + item.sender.username"
                            >
                                <img
                                    :src="item.sender.profileImageUrlThumb"
                                    alt="" class="PublicChat__profilePicture"/>
                            </a>

                           <div
                               v-if="item.sender.id === this.DP.authenticatedUser.id"
                           >
                                <img
                                    :src="item.sender.profileImageUrlThumb"
                                    alt="" class="PublicChat__profilePicture"/>
                            </div>
                        </div>
                        <div class="PublicChat__body clearfix">
                            <div class="PublicChat__header">
                                <a
                                    v-if="item.sender.id !== this.DP.authenticatedUser.id"
                                    :href="this.DP.singleProfileUrl + item.sender.username"
                                >
                                    <strong class="primary-font">{{ item.sender.username }}</strong>
                                </a>

                                <span
                                    v-if="item.sender.id === this.DP.authenticatedUser.id"
                                >
                                    <strong class="primary-font">{{ item.sender.username }}</strong>
                                </span>

                                <small class="pull-right PublicChat__timeAgo">
                                    <span class="glyphicon glyphicon-time"></span> {{ item.publishedAtHumanReadable }}
                                </small>
                            </div>
                            <p>
                                {{ item.body }}
                            </p>
                        </div>
                    </li>
                </ul>

                <div
                    v-if="displayedMessages.length === 0 && !this.fetchingInitial"
                    class="PublicChat__noMessages"
                    style="display: flex; align-items: center; justify-content: center">
                    <span>Geen berichten ...</span>
                </div>

                <!--                <div-->
                <!--                    class="fetchMoreButton"-->
                <!--                >-->
                <!--                    {{ this.$parent.chatTranslations['older_messages'] }}-->
                <!--                    <i class="material-icons">-->
                <!--                        get_app-->
                <!--                    </i>-->
                <!--                </div>-->

                <div
                    v-if="fetchingInitial"
                    class="fetchingMessages"
                >
                    <div class="loader"></div>
                </div>

                <!--                <div-->
                <!--                    v-if="allMessagesFetched && (displayedMessages.length >= messagesPerRequest)"-->
                <!--                    class="allMessagesFetched"-->
                <!--                >-->
                <!--                    {{ this.$parent.chatTranslations['no_more_messages'] }}-->
                <!--                </div>-->

                <!--                <div-->
                <!--                    v-if="allMessagesFetched && displayedMessages.length === 0"-->
                <!--                    class="allMessagesFetched"-->
                <!--                >-->
                <!--                    {{ this.$parent.chatTranslations['no_messages_yet'] }}-->
                <!--                </div>-->
            </div>
            <div class="panel-footer PublicChat__panelFooter">
                <form :action="this.DP.postChatItemRoute" method="POST">
                    <input
                        type="hidden"
                        name="_token"
                        :value="DP.csrfToken"
                    >

                    <input
                        type="hidden"
                        name="sender_id"
                        :value="DP.authenticatedUser.id"
                    >

                    <input
                        type="hidden"
                        name="type"
                        :value="DP.publicChatItemPeasantType"
                    >

                    <textarea
                        v-bind:style="{ height: computedTextareaHeight }"

                        :placeholder="this.$parent.chatTranslations ? this.$parent.chatTranslations['your_message'] : ''"
                        class="form-control PublicChat__textarea JS--PublicChat__textarea"
                        id="text"
                        name="text"
                        maxlength="200"
                        rows="2"
                        v-model="text"
                    ></textarea>
                    <div class="text-right">
                        <span class="label label-default JS--PublicChat__countChars PublicChat__countChars">{{ characterCount }}/<span
                            class="maxCharacters">200</span></span>
                    </div>
                    <div class="text-center PublicChat__submitButton">
                        <button
                            v-if="this.DP.authenticatedUser.account.credits > 0 && this.$parent.chatTranslations"
                            class="btn"
                            type="submit"
                        >
                            {{ this.$parent.chatTranslations['post_new_message'] }}
                        </button>

                        <a
                            v-if="this.DP.authenticatedUser.account.credits === 0 && this.$parent.chatTranslations"
                            class="btn"
                            :href="DP.creditsUrl"
                        >
                            {{ this.$parent.chatTranslations['buy_credits_to_post'] }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],

        data() {
            return {
                chatTranslations: undefined,
                chatItems: undefined,
                text: '',
                height: '42px',
                intervalToFetchMessages: undefined,
                currentHighestMessageId: undefined,
                offset: 0,
                messagesPerRequest: 10,
                checkingForNewAndShowing: false,
                fetchingInitial: true,
                displayedMessages: [],
                sendingMessage: false,
                DP: DP
            };
        },

        created() {
            this.fetchMessagesAndListenToChannel();
        },

        methods: {
            fetchMessagesAndListenToChannel() {
                this.fetchMessagesAndPopulate();

                this.intervalToFetchMessages = setInterval(() => {
                    if (!this.checkingForNewAndShowing) {
                        this.checkForNewMessagesAndShowThem();
                    }
                }, 10000);
            },

            // sendMessage() {
            //     this.sendingMessage = true;
            //
            //     let data = new FormData();
            //     data.append('body', this.text);
            //     data.append('sender_id', DP.authenticatedUser.id);
            //     data.append('type', '2');
            //
            //     const config = {
            //         headers: {
            //             'Content-Type': 'multipart/form-data'
            //         }
            //     };
            //
            //     axios.post('/api/public-chat/items', data, config).then(response => {
            //         console.log(response.data);
            //
            //         this.sendingMessage = false;
            //
            //     }).catch((error) => {
            //         this.sendingMessage = false;                fetchingOlderMessages: true,

            //     });
            // },

            checkForNewMessagesAndShowThem() {
                if (this.currentHighestMessageId === undefined) {
                    this.fetchMessagesAndPopulate();

                    return;
                }

                this.checkingForNewAndShowing = true;

                const config = {
                    headers: {
                        'Authorization': 'Bearer ' + DP.authenticatedUser.api_token
                    }
                }

                axios.get(
                    '/api/public-chat/items-with-higher-id-than/' + this.currentHighestMessageId + '/' + DP.authenticatedUser.meta.gender + '/' + DP.authenticatedUser.meta.looking_for_gender,
                    config
                ).then(response => {
                    let messages = response.data;

                    if (messages.length > 0) {
                        this.addMessagesToBeDisplayed(messages);

                    } else {
                        this.checkingForNewAndShowing = false;
                    }
                });
            },

            fetchMessagesAndPopulate() {
                const config = {
                    headers: {
                        'Authorization': 'Bearer ' + DP.authenticatedUser.api_token
                    }
                }

                axios.get(
                    '/api/public-chat/items/' + DP.authenticatedUser.meta.gender + '/' + DP.authenticatedUser.meta.looking_for_gender + '/0/40',
                    config
                ).then(response => {
                    this.chatItems = response.data;

                    if (this.chatItems.length > 0) {
                        this.offset += this.messagesPerRequest;

                        this.addMessagesToBeDisplayed(this.chatItems);
                    }

                    this.fetchingInitial = false;
                }).catch((error) => {
                    this.fetchingInitial = false;
                });
            },

            addMessagesToBeDisplayed: function (messages) {
                let messageIds = messages.map(message => {
                    return message.id;
                });

                this.currentHighestMessageId = Math.max.apply(null, messageIds);

                messages.reverse().forEach(message => {
                    this.displayedMessages.unshift(message);
                });

                setTimeout(() => {
                    this.scrollChatToTop();
                }, 200);

                this.checkingForNewAndShowing = false;
            },

            scrollChatToTop() {
                var objDiv = document.getElementById('PublicChat__panelBody');
                objDiv.scrollTop = 0;
            },

            increaseTextareaHeight: function (event) {
                this.height = '100px';
            },
            decreaseTextareaHeight: function (event) {
                this.height = '42px';
            }
        },

        computed: {
            characterCount() {
                return this.text.length;
            },

            computedTextareaHeight: function () {
                return this.height;
            }
        },
    }
</script>