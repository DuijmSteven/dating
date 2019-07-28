/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import isUndefined from "admin-lte/bower_components/moment/src/lib/utils/is-undefined";

require('./bootstrap');

window.Vue = require('vue/dist/vue.js');

import VuejsDialog from 'vuejs-dialog';
import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js';


import VueMq from 'vue-mq';
import moment from 'moment';

Vue.use(VueMq, {
    breakpoints: {
        xs: 768,
        sm: 992,
        md: 1200,
        lg: Infinity,
    }
});

Vue.use(VuejsDialog);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('private-chat-manager', require('./components/private-chat/PrivateChatManager.vue'));
Vue.component('private-chat', require('./components/private-chat/PrivateChat.vue'));
Vue.component('chat-message', require('./components/private-chat/ChatMessage.vue'));
Vue.component('chat-form', require('./components/private-chat/ChatForm.vue'));

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mm')
    }
});

const app = new Vue({
    el: '#app',

    props: [],

    data: {
        conversationPartners: [],
        previousConversationPartnersResponse: undefined,
        currentConversationPartnersResponse: undefined,
        intervalToFetchPartners: undefined
    },

    created() {
        if (!(isUndefined(DP.authenticatedUser) || DP.authenticatedUser == null)) {
            this.getConversationPartners();

            this.intervalToFetchPartners = setInterval(() => {
                this.getConversationPartners()
            }, 5000);
        }
    },

    methods: {
        setConversationActivityForUser: function (conversation, value) {
            axios.get('/api/conversations/set-conversation-activity-for-user/' + conversation.currentUser.id + '/' + conversation.otherUser.id + '/' + conversation.currentUser.id + '/' + value).then(
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

/**
 * Other Javascript
 */
require("jquery-ui/ui/widgets/datepicker");
require("jquery-ui/ui/widgets/autocomplete");

let cityList;

function getCoordinatesAndFillInputs() {
    var geocoder = new google.maps.Geocoder();

    if ($.inArray($('.JS--autoCompleteCites').val(), cityList) > 0 || isUndefined(cityList)) {
        geocoder.geocode({'address': $('.JS--autoCompleteCites').val() + ', nl'}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $('.js-hiddenLatInput').val(results[0].geometry.location.lat());
                $('.js-hiddenLngInput').val(results[0].geometry.location.lng());
            } else {
                $('.js-hiddenLatInput').val('');
                $('.js-hiddenLngInput').val('');
            }
        });
    }
    else {
        $('.js-hiddenLatInput').val('');
        $('.js-hiddenLngInput').val('');
    }
}

$(window).ready(function () {
    require('./global_helpers');

    if ($('.Shoutbox').length > 0) {
        require('./modules/shoutbox');
    }

    if ($('.JS--Search__autoCompleteCites').length > 0) {
        require('./modules/search');
    }

    if ($('.JS--datepicker__date').length > 0) {
        $('.datepicker__date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    }

    if ($('.JS--autoCompleteCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/api/cities/nl')
            .done(function (response) {
                cityList = response.cities;


                $('.JS--autoCompleteCites').autocomplete({
                    source: response.cities,
                    minLength: 2
                })
            }).fail(function () {
        });
    }

    if ($('.JS--Search').length > 0 || $('.JS--Edit-Profile').length > 0) {
        getCoordinatesAndFillInputs();

        $('.JS--autoCompleteCites').keyup(function () {
            getCoordinatesAndFillInputs();
        });
    }

    if ($('.JS--Search').length > 0) {
        const $searchRadiusInput = $('.JS--radiusSearchInput');

        if ($('.JS--autoCompleteCites').val().length > 0) {
            $searchRadiusInput.removeClass('hidden');
        }

        $('.JS--autoCompleteCites').keyup(function () {
            if ($('.JS--autoCompleteCites').val().length > 0) {
                if ($searchRadiusInput.hasClass('hidden')) {
                    $searchRadiusInput.removeClass('hidden');
                }
            } else {
                if (!$searchRadiusInput.hasClass('hidden')) {
                    $searchRadiusInput.addClass('hidden');
                }
            }
        });
    }

    if ($('.modalImage').length > 0) {
        $(".modalImage").on("click", function() {
            $('#imagePreview').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
            $('#imageModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
        });
    }

});



