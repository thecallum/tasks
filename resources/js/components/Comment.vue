<template>
    <div class="comment">
        <div class="comment-head">
            <div class="comment-user">{{ comment.name }}</div>
            <div class="comment-time">
                <timeago :datetime="comment.updated_at"></timeago>
            </div>
        </div>
        <div class="comment-message">{{ comment.comment }}</div>
        <div class="comment-control has-margin-top-5">
            <button class="comment-button" @click="deleteComment">
                Delete
            </button>
        </div>
    </div>
</template>

<script>
const Form = require("../Form.js");

export default {
    data() {
        return {
            form: new Form()
        };
    },
    props: {
        comment: Object
    },
    methods: {
        deleteComment() {
            if (confirm("Are you sure you want to delete this comment?")) {
                this.form
                    .delete("/comments/" + this.comment.id)
                    .then(response => {
                        console.log("response", response);
                        this.$store.commit("deleteComment", {
                            comment: this.comment
                        });
                    })
                    .catch(error => {
                        console.log("error", error);
                    });
            }
        }
    }
};
</script>
