<template>
    <div :class="'PrivateChatItem__message__container PrivateChatItem__message__container--' + message.user">
        <div :class="'PrivateChatItem__message PrivateChatItem__message--' + message.user">
            <a
                v-if="message.attachment"
                href="#"
                class="modalImage"
            >
                <img class="PrivateChatItem__image"
                     v-bind:src="messageAttachmentUrl(this.conversation.id, message.attachment.filename)"
                     alt=""
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
            messageAttachmentUrl(conversationId, $filename) {
                return DP.conversationsCloudUrl + '/' + conversationId + '/attachments/' + $filename
            },
            showModal() {
                $(".modalImage").on("click", function(event) {
                    event.preventDefault();
                    $('#imagePreview').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
                    $('#imageModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
                });
            }
        }
    };
</script>
