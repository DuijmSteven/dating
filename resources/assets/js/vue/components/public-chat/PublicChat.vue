<template>
    <div class="Tile PublicChat">
        <div class="Tile__body PublicChat__panel">
            <div class="PublicChat__panelBody" id="PublicChat__panelBody">
                <ul class="PublicChat__chat">
                    <li
                        v-for="(item, index) in displayedMessages"
                        class="clearfix"
                    >
                        <span class="PublicChat__Img pull-left">
                            <a href="#">
                                <img
                                    :src="item.sender.profileImageUrlThumb"
                                    alt="" class="PublicChat__profilePicture"/>
                            </a>
                        </span>
                        <div class="PublicChat__body clearfix">
                            <div class="PublicChat__header">
                                <a href="#">
                                    <strong class="primary-font">{{ item.sender.username }}</strong>
                                </a>
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
            </div>
            <div class="panel-footer PublicChat__panelFooter">
                <form :action="DP.postChatItemRoute" method="POST">
                    <textarea
                        v-bind:style="{ height: computedTextareaHeight }"
                        v-on:focus="increaseTextareaHeight()"
                        v-on:blur="decreaseTextareaHeight()"
                        :placeholder="chatTranslations ? chatTranslations['your_message'] : ''"
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
                            v-if="chatTranslations"
                            class="btn"
                            type="submit"
                        >
                            {{ chatTranslations['post_new_message'] }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
        ],

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
            axios.get('/api/' + DP.authenticatedUser.id + '/chat-translations').then(response => {
                this.chatTranslations = response.data;
            });

            this.fetchMessagesAndListenToChannel();
        },

        methods: {
            fetchMessagesAndListenToChannel() {
                this.fetchMessagesAndPopulate();

                this.intervalToFetchMessages = setInterval(() => {
                    if (this.currentHighestMessageId !== undefined && !this.checkingForNewAndShowing) {
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
            //         this.sendingMessage = false;
            //     });
            // },

            checkForNewMessagesAndShowThem() {
                this.checkingForNewAndShowing = true;

                axios.get(
                    '/api/public-chat/items-with-higher-id-than/' + this.currentHighestMessageId + '/' + DP.authenticatedUser.meta.gender + '/' + DP.authenticatedUser.meta.looking_for_gender).then(response => {
                    let messages = response.data;

                    if (messages.length > 0) {
                        this.addMessagesToBeDisplayed(messages);

                    } else {
                        this.checkingForNewAndShowing = false;
                    }
                });
            },

            fetchMessagesAndPopulate() {
                axios.get(
                    '/api/public-chat/items/' + DP.authenticatedUser.meta.gender + '/' + DP.authenticatedUser.meta.looking_for_gender + '/0/10').then(response => {
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