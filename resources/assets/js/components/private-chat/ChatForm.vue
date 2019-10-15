<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <div v-if="url" id="PrivateChatItem__imagePreview" class="PrivateChatItem__imagePreview">
            <img v-if="url" :src="url"/>
        </div>

        <div class="PrivateChatItem__textarea__container">
            <textarea id="PrivateChatItem__textarea" class="PrivateChatItem__textarea"
                      placeholder="Uw bericht..."
                      v-model.trim="text"
                      v-on:keyup.enter="sendMessage"
                      @focus="removeNotificationClass(); $parent.setConversationActivityForUserFalse()"
            >
            </textarea>

            <i
                class="material-icons material-icon sendMessage"
                v-on:click="sendMessage"
            >send</i>
        </div>
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
        },

        methods: {
            sendMessage() {
                if (this.text.length > 0 || this.file != null) {
                    this.$emit('message-sent', {
                        text: this.text,
                        attachment: this.file != null ? this.file : null
                    });

                    this.text = '';
                    this.file = null;
                    this.url = null;
                }

                if ($('.PrivateChatItem--' + this.index).hasClass('PrivateChatItem--showingPreview')) {
                    $('.PrivateChatItem--' + this.index).removeClass('PrivateChatItem--showingPreview');
                }
            },

            previewImage(e) {
                this.file = e.target.files[0];
                this.url = URL.createObjectURL(this.file);

                $('.PrivateChatItem--' + this.index).addClass('PrivateChatItem--showingPreview');
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