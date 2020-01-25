<template>
    <div style="margin-top: 15px">
        <h2 class="subtitle">Comments</h2>

        <textarea
            class="textarea"
            placeholder="Write a comment..."
            v-model="form.comment"
        ></textarea>

        <div
            v-if="form.comment.length > 0"
            class="has-margin-top-15 is-flex align-items-center"
        >
            <button @click="handleSubmit" class="button is-success">
                Save
            </button>
            <ButtonClose
                :close="clear"
                class="has-margin-left-15"
            ></ButtonClose>
        </div>

        <div class="comments-list">
            <Comment
                v-for="(comment, index) in comments"
                :key="index"
                :comment="comment"
            ></Comment>
        </div>
    </div>
</template>

<script>
const Comment = require("./Comment.vue").default;
const Form = require("../Form.js");
const ButtonClose = require("./ButtonClose.vue").default;

export default {
    props: {
        card: Object
    },
    components: {
        Comment,
        ButtonClose
    },
    data() {
        return {
            form: new Form(["comment"])
        };
    },
    methods: {
        clear() {
            this.form.reset();
        },
        handleSubmit() {
            this.form
                .post("/comments/" + this.card.id)
                .then(response => {
                    console.log("response", response);
                    this.$store.commit("addComment", {
                        comment: response.data
                    });
                    this.form.reset();
                })
                .catch(error => {
                    console.log("error", error);
                });
        }
    },
    computed: {
        comments() {
            return this.$store.getters.cardComments(this.card.id);
        }
    }
};
</script>

<style lang="scss" scoped>
.comments-list {
    margin-top: 30px;
}
</style>
