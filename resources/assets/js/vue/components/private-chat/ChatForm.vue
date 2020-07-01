<template>
    <div class="PrivateChatItem__textarea__wrapper">
        <div v-if="imagePreviewUrl" id="PrivateChatItem__imagePreview" class="PrivateChatItem__imagePreview">
            <i
                v-on:click="clearImagePreview()"
                class="material-icons material-icon PrivateChatItem__clearImagePreview"
            >clear
            </i>
            <img v-if="imagePreviewUrl" :src="imagePreviewUrl"/>
        </div>

        <div class="PrivateChatItem__textarea__container">
            <textarea
                id="PrivateChatItem__textarea"
                class="PrivateChatItem__textarea JS--PrivateChatItem__textarea"
                maxlength="1000"
                :placeholder="chatTranslations ? chatTranslations['your_message'] : ''"
                v-on:keydown="textareaKeyDown"
                @focus="chatFocused()"
                v-model="text"
                :disabled="this.disableTextarea"
                ref="textarea"
            ></textarea>

            <div
                class="PrivateChatItem__emoticonsIcon"
            >
                <emoji-picker
                    :data="emojiData"
                    @emoji:picked="handleEmojiPicked"
                />
            </div>

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
    import emojiData from '@zaichaopan/emoji-picker/data/emojis.json';

    export default {
        props: [
            'user',
            'index',
            'conversation',
            'sendingMessage',
            'chatTranslations',
        ],

        data() {
            return {
                text: '',
                imagePreviewUrl: null,
                file: null,
                lineCount: 1,
                textBeingSent: '',
                fileBeingSent: null,
                imagePreviewUrlBackup: null,
                userCredits: undefined,
                disableTextarea: false,
                showEmoticonPicker: false,
                emojiData: emojiData,
                textarea: undefined
            }
        },

        mounted: function () {
            this.$root.$on('messageSent', () => {
                this.text = '';
                this.file = null;
                this.imagePreviewUrl = null;
            });

            this.$root.$on('errorMessageSent', () => {
                this.text = this.textBeingSent;
                this.file = this.fileBeingSent;
                this.imagePreviewUrl = this.imagePreviewUrlBackup;

                this.textBeingSent = '';
                this.fileBeingSent = null;
                this.imagePreviewUrlBackup = null;
            });

            this.$root.$on('userCreditsUpdated', (data) => {
                this.userCredits = data.credits;
            });

            this.getUserCredits();
        },

        computed: {
            characterCount() {
                return [...this.text].length;
            }
        },

        methods: {
            // clickedOutsideOfEmoticonPicker() {
            //     if (this.showEmoticonPicker) {
            //         this.closeEmoticonPicker();
            //     }
            //
            //     console.log(this.showEmoticonPicker);
            // },
            // toggleEmoticonPicker() {
            //     this.showEmoticonPicker = !this.showEmoticonPicker;
            //
            //     console.log(this.showEmoticonPicker);
            // },
            closeEmoticonPicker() {
                this.showEmoticonPicker = false;
            },
            handleEmojiPicked(emoji) {
                this.closeEmoticonPicker();

                let newText = '';
                let textarea = this.$refs.textarea;

                const oldTextareaValue = textarea.value;

                const cursorPosition = textarea.selectionStart;

                const textBeforeCursor = oldTextareaValue.substring(0, cursorPosition);
                const textAfterCursor = oldTextareaValue.substring(cursorPosition + 1);

                newText = textBeforeCursor + emoji + textAfterCursor;
      

                this.text = newText;
            },
            getUserCredits: function () {
                axios.get('/api/users/' + parseInt(DP.authenticatedUser.id) + '/credits').then(
                    response => {
                        this.userCredits = response.data;
                    }
                );
            },
            chatFocused() {
                this.removeNotificationClass();
                this.$parent.setConversationActivityForUserFalse();
            },
            textareaKeyDown(event) {
                if (this.userCredits === 0) {
                    this.file = null;
                    this.imagePreviewUrl = null;

                    setTimeout(() => {
                        this.text = '';
                    }, 50);

                    this.disableTextarea = true;

                    this.$emit('show-no-credits');
                } else if (event.key === "Enter") {
                    this.sendMessage();
                }
            },
            sendMessage() {
                if (!this.sendingMessage) {
                    this.textBeingSent = this.text;
                    this.text = '';

                    this.fileBeingSent = this.file;
                    this.file = null;

                    this.imagePreviewUrlBackup = this.imagePreviewUrl;
                    this.imagePreviewUrl = null;

                    if ([...this.textBeingSent].length > 0 || this.fileBeingSent != null) {
                        this.$emit('message-sent', {
                            text: this.textBeingSent,
                            attachment: this.fileBeingSent != null ? this.fileBeingSent : null
                        });
                    }

                    if ($('.PrivateChatItem--' + this.index).hasClass('PrivateChatItem--showingPreview')) {
                        $('.PrivateChatItem--' + this.index).removeClass('PrivateChatItem--showingPreview');
                    }
                }
            },
            clearImagePreview() {
                this.imagePreviewUrl = null;
                this.file = null;

                $('.PrivateChatItem__imageAttachmentInput').prop('value', '');

                $('.PrivateChatItem--' + this.index).removeClass('PrivateChatItem--showingPreview');
            },
            previewImage(e) {
                this.file = e.target.files[0];
                this.imagePreviewUrl = URL.createObjectURL(this.file);

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