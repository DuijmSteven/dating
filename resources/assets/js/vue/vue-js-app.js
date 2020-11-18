import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";
import {isNull} from "lodash";

import { requestConfig } from './common-imports';

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
            gettingOnlineIds: false,
            managerMaximized: true,
            conversations: [],
            countConversationsWithNewMessages: 0,
            fetchingUserConversations: false,
            conversationManagerDataFullyLoaded: false
        },

        created() {
            if (!(isUndefined(DP.authenticatedUser) || DP.authenticatedUser == null)) {
                this.getConversationPartners();
                this.getUserCredits();
                this.getOnlineUserIds();
                this.fetchUserConversations(true);

                setInterval(() => {
                    if (!this.fetchingUserConversations) {
                        this.fetchUserConversations();
                    }
                }, 10000);

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
            toggleManager: function () {
                $('#PrivateChatManager__body').slideToggle('fast');
                this.managerMaximized = !this.managerMaximized;

                if (['xs', 'sm'].includes(this.$mq) && this.managerMaximized) {
                    $('body').css('overflow-y', 'hidden');
                } else {
                    $('body').css('overflow-y', 'scroll');
                }

                let managerState = this.managerMaximized ? 1 : 0;

                axios.get(
                    '/api/conversations/conversation-manager-state/' +
                    parseInt(DP.authenticatedUser.id) + '/' +
                    managerState,
                    requestConfig
                ).then(
                    response => {}
                );
            },

            fetchUserConversations: function (fetchStatus) {
                this.fetchingUserConversations = true;

                axios.get(
                    '/api/conversations/' + DP.authenticatedUser.id,
                    requestConfig
                ).then(response => {
                    this.conversations = response.data;
                    this.countConversationsWithNewMessages = 0;

                    this.conversations.map(conversation => {
                        if (conversation.user_a_id === DP.authenticatedUser.id) {
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
                    });

                    if (fetchStatus) {
                        this.fetchConversationManagerStatus();
                    }

                    this.fetchingUserConversations = false;
                }).catch(function (error) {
                    this.fetchingUserConversations = false;
                }.bind(this));
            },

            getUserCredits: function () {
                axios.get(
                    '/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits',
                    requestConfig
                ).then(
                    response => {
                        this.userCredits = response.data;
                    }
                );
            },
            getOnlineUserIds: function () {
                this.gettingOnlineIds = true;

                axios.get(
                    '/api/users/online/ids',
                    requestConfig
                ).then(
                    response => {
                        this.onlineUserIds = response.data;
                        this.gettingOnlineIds = false;
                    }
                );
            },
            setConversationActivityForUser: function (conversation, value) {
                axios.get(
                    '/api/conversations/set-conversation-activity-for-user/' + conversation.currentUserId + '/' + conversation.otherUserId + '/' + conversation.currentUserId + '/' + value,
                    requestConfig
                ).then(
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
            fetchConversationManagerStatus: function () {
                axios.get(
                    '/api/conversations/conversation-manager-state/' + parseInt(DP.authenticatedUser.id),
                    requestConfig
                ).then(
                    response => {
                        this.conversationManagerState = response;

                        if (!(this.$mq === 'xs' || this.$mq === 'sm')) {
                            this.managerMaximized = this.conversationManagerState.data === 1;

                            if (this.managerMaximized) {
                                $('.PrivateChatManager').addClass('maximized');
                            }
                        }

                        this.conversationManagerDataFullyLoaded = true;
                    }
                );
            },
            getConversationPartners: function () {
                this.gettingPartners = true;

                axios.get(
                    '/api/conversations/conversation-partner-ids/' + parseInt(DP.authenticatedUser.id),
                        requestConfig
                    ).then(
                    response => {
                        if (response.data && response.data.length > 0) {
                            this.currentConversationPartnersResponse = response;

                            response.data.forEach((key, index) => {
                                if (index <= 2) {
                                    let split = key.split(':');

                                    let userBId = +split[0];
                                    let state = split[1];

                                    if (
                                        !this.conversationPartners.map(partner => partner.id).includes(userBId) &&
                                        !['xs', 'sm', 'md'].includes(this.$mq)
                                    ) {
                                        this.addChat(DP.authenticatedUser.id, userBId, state);
                                    }
                                }
                            });

                            this.previousConversationPartnersResponse = this.currentConversationPartnersResponse;
                        }

                        this.gettingPartners = false;
                    }
                );
            },
            addChat: function (currentUserId, userBId, state = '1', persist = false) {
                if (this.conversationPartners.length > 2) {
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
                axios.get(
                    '/api/users/' + userBId,
                    requestConfig
                ).then(
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
                                state,
                                requestConfig
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
