<template>
    <div class="comment">
        <div class="comment-head">
            <div class="comment-user">{{ comment.name }}</div>
            <div class="comment-time">
                <timeago :datetime="comment.updated_at"></timeago>
            </div>
        </div>
        <div class="comment-message">{{ comment.comment }}</div>
    </div>
</template>

<script>
export default {
    props: {
        comment: Object
    }
};
</script>
