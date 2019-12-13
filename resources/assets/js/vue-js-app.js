import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

if ($('#app').length > 0) {
    const app = new Vue({
        el: '#app',

        props: [],

        data: {
            conversationPartners: [],
            previousConversationPartnersResponse: undefined,
            currentConversationPartnersResponse: undefined,
            intervalToFetchPartners: undefined,
            userCredits: undefined
        },

        created() {
            if (!(isUndefined(DP.authenticatedUser) || DP.authenticatedUser == null)) {
                this.getConversationPartners();
                this.getUserCredits();

                this.intervalToFetchPartners = setInterval(() => {
                    this.getConversationPartners()
                }, 5000);
            }
        },

        methods: {
            getUserCredits: function () {
                axios.get('/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits').then(
                    response => {
                        this.userCredits = response.data;

                        console.log(this.userCredits);
                    }
                );
            },
            setConversationActivityForUser: function (conversation, value) {
                axios.get('/api/conversations/set-conversation-activity-for-user/' + conversation.currentUserId + '/' + conversation.otherUserId + '/' + conversation.currentUserId + '/' + value).then(
                    response => {
                        console.log(response);
                    }
                );
            },
            getConversationPartners: function () {
                axios.get('/api/conversations/conversation-partner-ids/' + parseInt(DP.authenticatedUser.id)).then(
                    response => {
                        this.currentConversationPartnersResponse = response;

                        if (this.previousConversationPartnersResponse === undefined || this.previousConversationPartnersResponse.data !== this.currentConversationPartnersResponse.data) {
                            for (let key in response.data) {
                                let split = response.data[key].split(':');

                                if (!this.conversationPartners.map(partner => partner.id).includes(+split[0])) {
                                    this.addChat(DP.authenticatedUser.id, split[0], split[1]);
                                }
                            }

                            this.previousConversationPartnersResponse = this.currentConversationPartnersResponse;
                        }
                    }
                );
            },
            addChat: function (currentUserId, userBId, state = '1', persist = false) {
                if (this.conversationPartners.length > 4) {
                    return false;
                }

                let isConversationOpen = false;
                let openConversationIndex;

                this.conversationPartners.forEach(function (partner, index) {
                    if (partner.id === userBId) {
                        isConversationOpen = true;
                        openConversationIndex = index;
                    }
                });

                if (!isConversationOpen) {
                    this.fetchUserAndAddToPartners(userBId, state, persist);
                } else {
                    $('.PrivateChatItem--' + openConversationIndex + ' textarea').focus();
                    $('.PrivateChatItem').removeClass('focus');
                    $('.PrivateChatItem--' + openConversationIndex).addClass('focus');
                }
            },
            fetchUserAndAddToPartners: function (userBId, state, persist = false) {
                axios.get('/api/users/' + userBId).then(
                    response => {
                        let partnerData = response.data;
                        partnerData.chatState = state;

                        this.conversationPartners.push(partnerData);

                        if (persist) {
                            axios.get(
                                '/api/conversations/conversation-partner-ids/add/' +
                                parseInt(DP.authenticatedUser.id) +
                                '/' +
                                parseInt(userBId) +
                                '/' +
                                state
                            ).then(
                                response => {}
                            );
                        }

                        this.$nextTick(() => {
                            $('.PrivateChatItem--' + (this.conversationPartners.length - 1) + ' textarea').focus();
                            $('.PrivateChatItem').removeClass('focus');
                            $('.PrivateChatItem--' + (this.conversationPartners.length - 1)).addClass('focus');


                            if (state === '1') {
                                $('#PrivateChatItem__body--' + (this.conversationPartners.length - 1)).css('display', 'block');
                            } else {
                                $('#PrivateChatItem__body--' + (this.conversationPartners.length - 1)).css('display', 'none');
                            }
                        });
                    }
                );
            }
        }
    });
}
