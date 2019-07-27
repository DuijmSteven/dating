<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <div v-if="url" id="PrivateChatItem__imagePreview" class="PrivateChatItem__imagePreview">
            <img v-if="url" :src="url"/>
        </div>

        <textarea id="test123" class="PrivateChatItem__textarea"
                  placeholder="Uw bericht..."
                  v-model.trim="text"
                  v-on:keyup.enter="sendMessage"
                  @focus="removeNotificationClass(); $parent.setConversationActivityForUserFalse()"
        >
        </textarea>
        <div class="PrivateChatItem__textarea__buttons">
            <label style="margin-bottom: 0; cursor: pointer">
                <i class="material-icons">attach_file</i>
                <form enctype="multipart/form-data" @change="previewImage($event)">
                    <input type="file" id="attachment" name="attachment" style="display: none;">
                </form>
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
            'index',
            'conversation'
        ],

        data() {
            return {
                text: '',
                url: null,
                file: null
            }
        },

        mounted: function () {
/*            var txt = $('.PrivateChatItem__textarea'),
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

            });*/
        },

        methods: {
            sendMessage() {
                if (this.text.length > 0) {
                    this.$emit('message-sent', {
                        text: this.text,
                        attachment: this.file != null ? this.file : null
                    });

                    this.text = '';
                    this.file = null;
                    this.url = null;
                }
            },

            previewImage(e) {
                this.file = e.target.files[0];
                this.url = URL.createObjectURL(this.file);
            },

            removeNotificationClass() {
                if (
                    $('#PrivateChatItem__head--' + this.index)
                        .hasClass('PrivateChatItem__head__notify')
                ) {
                    $('#PrivateChatItem__head--' + this.index).removeClass('PrivateChatItem__head__notify');
                }
            }
        }
    }
</script>