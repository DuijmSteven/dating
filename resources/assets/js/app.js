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

Vue.component('chat-messages', require('./components/ChatMessages.vue'));
Vue.component('chat-form', require('./components/ChatForm.vue'));

const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },

    created() {
        this.fetchMessages();

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                /*this.messages.push({
                    message: e,
                    user: e
                });*/
                console.log(e);
                console.log(e.user.username);
            });
    },

    methods: {
        fetchMessages() {
            axios.get('/conversations/1/messages').then(response => {
                this.messages = response.data;
            });
        },

        addMessage(message) {
            axios.post('/conversations', {
                message: message.message,
                conversation_id: '1',
                sender_id: '44',
                recipient_id: '83'
            })
                .then(
                    axios.get('/conversations/1/messages').then(response => {
                        this.messages = response.data;
                    })
                );
        }
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



