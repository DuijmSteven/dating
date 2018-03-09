<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <textarea id="test123" class="PrivateChatItem__textarea"
                  placeholder="Type your message here..."
                  v-model.trim="text"
                  v-on:keyup.enter="sendMessage">
        </textarea>
        <div class="PrivateChatItem__textarea__buttons">
            <label style="margin-bottom: 0; cursor: pointer">
                <i class="material-icons">attach_file</i>
                <form enctype="multipart/form-data" v-on:change="sendAttachment">
                    <input type="file" id="attachment" name="attachment" style="display: none;">
                </form>
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                text: ''
            }
        },

        mounted: function () {
            var txt = $('.PrivateChatItem__textarea'),
                hiddenDiv = $(document.createElement('div')),
                content = null;

            txt.addClass('PrivateChatItem__textarea__textStuff');
            hiddenDiv.addClass('PrivateChatItem__textarea__hiddenDiv');

            $('.PrivateChatItem__textarea__wrapper').append(hiddenDiv);

            txt.on('keyup', function () {

                content = $(this).val();

                content = content.replace(/\n/g, '<br>');
                hiddenDiv.html(content + '<br style="line-height: 3px">');

                $(this).css('height', hiddenDiv.height());

            });
        },

        methods: {
            sendMessage() {
                this.$emit('message-sent', {
                    text: this.text
                });

                this.text = ''
            },

            sendAttachment() {
            }
        }
    }
</script>