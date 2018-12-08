/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue/dist/vue.js');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('private-chat', require('./components/private-chat/PrivateChat.vue'));
Vue.component('chat-message', require('./components/private-chat/ChatMessage.vue'));
Vue.component('chat-form', require('./components/private-chat/ChatForm.vue'));

const app = new Vue({
    el: '#app',

    data: {
        conversationPartners: []
    },

    methods: {
        addChat: function (currentUserId, userBId) {
            console.log('Current user ID: ' + currentUserId);
            console.log('Chat partner ID: ' + userBId);

            let isConversationOpen = false;
            let openConversationIndex;

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
                $('.PrivateChatItem').removeClass('focus');
                $('.PrivateChatItem--' + openConversationIndex).addClass('focus');
            }
        },
    }
});

import VueMq from 'vue-mq'

Vue.use(VueMq, {
    breakpoints: {
        mobile: 450,
        tablet: 900,
        laptop: 1250,
        desktop: Infinity,
    }
})

/**
 * Other Javascript
 */

/*window.jQuery = require('jquery');
window.$ = window.jQuery;
require('bootstrap-sass');*/
require('jquery-autocomplete/jquery.autocomplete.js');

$(window).ready(function() {
    //console.log($.fn.tooltip.Constructor.VERSION);

    require('./global_helpers');

    if ($('.Shoutbox').length > 0) {
        require('./modules/shoutbox');
    }

    if ($('.JS--Search__autoCompleteCites').length > 0) {
        require('./modules/search');
    }
});



