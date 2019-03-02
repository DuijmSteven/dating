/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue/dist/vue.js');


import VueMq from 'vue-mq'

Vue.use(VueMq, {
    breakpoints: {
        xs: 768,
        sm: 992,
        md: 1200,
        lg: Infinity,
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('private-chat-manager', require('./components/private-chat/PrivateChatManager.vue'));
Vue.component('private-chat', require('./components/private-chat/PrivateChat.vue'));
Vue.component('chat-message', require('./components/private-chat/ChatMessage.vue'));
Vue.component('chat-form', require('./components/private-chat/ChatForm.vue'));

const app = new Vue({
    el: '#app',

    props: [],

    data: {
        conversationPartners: []
    },

    created() {
        axios.get('/api/conversations/conversation-partner-ids/' + parseInt(DP.authenticatedUser.id)).then(
            response => {
                for (let key in response.data) {
                    this.addChat(DP.authenticatedUser.id, response.data[key]);
                }
            }
        );
    },

    methods: {
        addChat: function (currentUserId, userBId) {
            if (this.conversationPartners.length > 4) {
                return false;
            }

            let isConversationOpen = false;
            let openConversationIndex;

            if (this.conversationPartners.map(partner => partner.id).indexOf(userBId) === -1) {
                axios.get(
                    '/api/conversations/conversation-partner-ids/add/' +
                    parseInt(DP.authenticatedUser.id) +
                    '/' +
                    parseInt(userBId)
                ).then(
                    response => {
                        console.log(response);
                    }
                );
            }

            this.conversationPartners.forEach(function (partner, index) {
                if (partner.id === userBId) {
                    isConversationOpen = true;
                    openConversationIndex = index;
                }
            });

            if (!isConversationOpen) {
                axios.get('/api/users/' + userBId).then(
                    response => {
                        this.conversationPartners.push(response.data);

                        this.$nextTick(() => {
                            $('.PrivateChatItem--' + (this.conversationPartners.length - 1) + ' textarea').focus();
                            $('.PrivateChatItem').removeClass('focus');
                            $('.PrivateChatItem--' + (this.conversationPartners.length - 1)).addClass('focus');
                        });
                    }
                );
            } else {
                $('.PrivateChatItem--' + openConversationIndex + ' textarea').focus();
                $('.PrivateChatItem').removeClass('focus');u<aa
                $('.PrivateChatItem--' + openConversationIndex).addClass('focus');
            }
        },
    }
});

/**
 * Other Javascript
 */

/*window.jQuery = require('jquery');
window.$ = window.jQuery;
require('bootstrap-sass');*/
//require('jquery-autocomplete/jquery.autocomplete.js');
require("jquery-ui/ui/widgets/datepicker");
require("jquery-ui/ui/widgets/autocomplete");

$(window).ready(function() {
    //console.log($.fn.tooltip.Constructor.VERSION);

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

    if ($('.js-autoCompleteCites').length > 0) {
        // Auto-completes Dutch cities in bot creation view text field
        $.getJSON(DP.baseUrl + '/api/cities/nl')
            .done(function (response) {
                $('.js-autoCompleteCites').autocomplete({
                    source: response.cities
                })
            }).fail(function () {
            console.log("Error: Ajax call to users/cities endpoint failed");
        });
    }

    $('.js-autoCompleteCites').keyup(function(){
        var geocoder =  new google.maps.Geocoder();

        geocoder.geocode( { 'address': $('.js-autoCompleteCites').val() + ', nl'}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $('.js-hiddenLatInput').val(results[0].geometry.location.lat());
                $('.js-hiddenLngInput').val(results[0].geometry.location.lng());
            } else {
                $('.js-hiddenLatInput').val('');
                $('.js-hiddenLngInput').val('');
            }
        });
    });
});



