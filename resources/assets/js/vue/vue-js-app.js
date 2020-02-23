import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";
import {isNull} from "lodash";

if ($('#app').length > 0) {
    const app = new Vue({
        el: '#app',

        props: [],

        data: {
            conversationPartners: [],
            previousConversationPartnersResponse: undefined,
            currentConversationPartnersResponse: undefined,
            intervalToFetchData: undefined,
            userCredits: undefined,
            onlineUserIds: undefined,
            chatTranslations: undefined,
            gettingPartners: false,
            gettingOnlineIds: false
        },

        created() {
            if (!(isUndefined(DP.authenticatedUser) || DP.authenticatedUser == null)) {
                this.getConversationPartners();
                this.getUserCredits();
                this.getOnlineUserIds();

                axios.get('/api/' + DP.authenticatedUser.id + '/chat-translations').then(response => {
                    this.chatTranslations = response.data;
                });

                this.intervalToFetchData = setInterval(() => {
                    if (!this.gettingPartners) {
                        this.getConversationPartners();
                    }

                    if (!this.gettingOnlineIds) {
                        this.getOnlineUserIds();
                    }
                }, 5000);
            }
        },

        methods: {
            getUserCredits: function () {
                axios.get('/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits').then(
                    response => {
                        this.userCredits = response.data;
                    }
                );
            },
            getOnlineUserIds: function () {
                this.gettingOnlineIds = true;

                axios.get('/api/users/online/ids').then(
                    response => {
                        this.onlineUserIds = response.data;
                        this.gettingOnlineIds = false;
                    }
                );
            },
            setConversationActivityForUser: function (conversation, value) {
                axios.get('/api/conversations/set-conversation-activity-for-user/' + conversation.currentUserId + '/' + conversation.otherUserId + '/' + conversation.currentUserId + '/' + value).then(
                    response => {
                    }
                );
            },
            fitRoundImageToContainer: function (element) {
                let containerHeight = element.height();
                let $imageToFit = $(element).find('img');
                let imageToFitHeight = $imageToFit.height();
                let imageToFitWidth = $imageToFit.width();

                if (imageToFitWidth > imageToFitHeight) {
                    $imageToFit.addClass('fitVertically');
                }
            },
            getConversationPartners: function () {
                this.gettingPartners = true;

                axios.get('/api/conversations/conversation-partner-ids/' + parseInt(DP.authenticatedUser.id)).then(
                    response => {
                        if (response.data && response.data.length > 0) {
                            this.currentConversationPartnersResponse = response;

                            response.data.forEach((key) => {
                                let split = key.split(':');

                                let userBId = +split[0];
                                let state = split[1];

                                if (!this.conversationPartners.map(partner => partner.id).includes(userBId) && !['xs', 'sm'].includes(this.$mq)) {
                                    this.addChat(DP.authenticatedUser.id, userBId, state);
                                }
                            });

                            this.previousConversationPartnersResponse = this.currentConversationPartnersResponse;
                            this.gettingPartners = false;
                        }
                    }
                );
            },
            addChat: function (currentUserId, userBId, state = '1', persist = false) {
                if (this.conversationPartners.length > 3) {
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
                                response => {
                                }
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
