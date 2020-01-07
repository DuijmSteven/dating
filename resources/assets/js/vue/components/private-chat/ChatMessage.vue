<template>
    <div :class="'PrivateChatItem__message__container PrivateChatItem__message__container--' + message.user">
        <div :class="'PrivateChatItem__message PrivateChatItem__message--' + message.user">
            <a
                v-if="message.attachment"
                href="#"
                class="modalImage"
            >
                <img class="PrivateChatItem__image"
                     v-bind:src="messageAttachmentUrl(this.conversation.id, message.attachment.filename, true)"
                     alt=""
                     v-bind:data-src="messageAttachmentUrl(this.conversation.id, message.attachment.filename)"
                >
            </a>
            <div>
                {{ message.text }} <span class="PrivateChatItem__message__createdAt">{{ message.createdAt | formatDate }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'message',
            'conversation'
        ],

        mounted() {
            this.showModal();
        },

        methods: {
            messageAttachmentUrl(conversationId, $filename, $thumb = false) {
                if (!$thumb) {
                    return DP.conversationsCloudUrl + '/' + conversationId + '/attachments/' + $filename
                } else {

                    let splitFilename = $filename.split('.');

                    let thumbFilePath = splitFilename[0] + '_thumb' + '.' + splitFilename[1];

                    return DP.conversationsCloudUrl + '/' + conversationId + '/attachments/' + thumbFilePath
                }
            },
            showModal() {
                $(".modalImage").on("click", function(event) {
                    event.preventDefault();
                    $('#imagePreview').attr('src', $(this).find('img').data('src')); // here asign the image to the modal when the user click the enlarge link
                    $('#imageModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
                });
            }
        }
    };
</script>
