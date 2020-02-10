<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <div v-if="url" id="PrivateChatItem__imagePreview" class="PrivateChatItem__imagePreview">
            <i
                v-on:click="clearImagePreview()"
                class="material-icons material-icon PrivateChatItem__clearImagePreview"
            >clear
            </i>
            <img v-if="url" :src="url"/>
        </div>

        <div class="PrivateChatItem__textarea__container">
            <textarea
                id="PrivateChatItem__textarea"
                class="PrivateChatItem__textarea JS--PrivateChatItem__textarea"
                maxlength="1000"
                :placeholder="chatTranslations['your_message']"
                v-on:keyup.enter="sendMessage"
                @focus="chatFocused()"
                v-model="text"
            ></textarea>

            <div class="PrivateChatItem__attachmentIcon">
                <label>
                    <i class="material-icons">attach_file</i>
                    <form enctype="multipart/form-data" @change="previewImage($event)">
                        <input
                            type="file"
                            accept=".png,.jpg,.jpeg"
                            id="attachment"
                            name="attachment"
                            style="display: none;"
                            class="PrivateChatItem__imageAttachmentInput"
                        >
                    </form>
                </label>
            </div>

            <i
                v-if="!sendingMessage"
                class="material-icons material-icon sendMessage"
                v-on:click="sendMessage"
            >send</i>

            <div
                v-if="sendingMessage"
                class="loader"
            ></div>

            <div class="PrivateChatItem__messageCounter">
                <span class="currentCount">{{ characterCount }}</span>/<span class="maxCharacters">1000</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
            'index',
            'conversation',
            'sendingMessage',
            'chatTranslations'
        ],

        data() {
            return {
                text: '',
                url: null,
                file: null,
                lineCount: 1
            }
        },

        mounted: function () {
            this.$root.$on('messageSent', () => {
                this.text = '';
                this.file = null;
                this.url = null;
            })
        },

        computed: {
            characterCount() {
                return this.text.length;
            }
        },

        methods: {
            chatFocused() {
                this.removeNotificationClass();
                this.$parent.setConversationActivityForUserFalse();
            },
            sendMessage() {
                if (this.text.length > 0 || this.file != null) {
                    this.$emit('message-sent', {
                        text: this.text,
                        attachment: this.file != null ? this.file : null
                    });
                }

                if ($('.PrivateChatItem--' + this.index).hasClass('PrivateChatItem--showingPreview')) {
                    $('.PrivateChatItem--' + this.index).removeClass('PrivateChatItem--showingPreview');
                }
            },
            clearImagePreview() {
                this.url = null;
                this.file = null;

                $('.PrivateChatItem__imageAttachmentInput').prop('value', '');

                $('.PrivateChatItem--' + this.index).removeClass('PrivateChatItem--showingPreview');
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