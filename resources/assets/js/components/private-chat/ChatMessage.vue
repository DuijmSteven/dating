<template>
    <div :class="'PrivateChatItem__message__container PrivateChatItem__message__container--' + message.user">
        <div :class="'PrivateChatItem__message PrivateChatItem__message--' + message.user">
            <img class="PrivateChatItem__image"
                 v-if="message.attachment"
                 v-bind:src="messageAttachmentUrl(this.conversation.id, message.attachment.filename)"
                 alt=""
            >
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

        methods: {
            messageAttachmentUrl(conversationId, $filename) {
                return DP.conversationsCloudUrl + '/' + conversationId + '/attachments/' + $filename
            },
        }
    };
</script>
