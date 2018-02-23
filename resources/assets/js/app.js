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
Vue.component('chat-messages', require('./components/private-chat/ChatMessages.vue'));
Vue.component('chat-form', require('./components/private-chat/ChatForm.vue'));

const app = new Vue({
    el: '#app',

    data: {
        messages: [],
        conversationPartners: [],
    },

    created() {
        Echo.private('chat')
            .listen('MessageSent', (e) => {
                this.messages.push({
                    message: e.conversationMessage.body,
                    user: e.user.username
                });
            });
    },

    methods: {
        addChat: function (currentUserId, userBId) {
            console.log('addChat()');

            axios.get('/api/users/' + userBId).then(response => {
                this.conversationPartners.push(response.data);
            });
        },
    }
});


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

    if ($('.Search').length > 0) {
        require('./modules/search');
    }
});



