<template>
    <div class="modal is-active">
        <div class="modal-background" @click="close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <div style="flex-grow: 1">
                    <input
                        type="text"
                        class="modal-card-title"
                        v-model="updateForm.name"
                    />
                    <p class="modal-description">
                        in list
                        <span class="modal-list-name">{{ listName }}</span>
                    </p>
                </div>
                <button
                    class="delete"
                    aria-label="close"
                    @click="close"
                ></button>
            </header>
            <section class="modal-card-body">
                <h2 class="subtitle">Description</h2>
                <div class="field">
                    <div v-if="!descriptionActive">
                        <div @click="showDescription">
                            <div
                                v-if="updateForm.description"
                                style="white-space: pre;"
                                v-html="updateForm.description"
                            ></div>
                            <div v-else>Add a more detailed description...</div>
                        </div>
                    </div>
                    <div v-else v-on-clickaway="hideDescription">
                        <div class="control">
                            <textarea
                                :style="{ height: descriptionHeight }"
                                class="textarea"
                                v-model="updateForm.description"
                                placeholder="Add a more detailed description..."
                            ></textarea>

                            <div class="edit-card-options">
                                <button class="button is-success" type="submit">
                                    Add List
                                </button>

                                <ButtonClose
                                    :close="hideDescription"
                                ></ButtonClose>
                            </div>
                        </div>
                    </div>

                    <Comments :comments="comments" :card="card"></Comments>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" @click="handleSubmit">
                    Save changes
                </button>
                <button class="button" @click="cancel">Cancel</button>
            </footer>
        </div>
    </div>
</template>

<script>
const eventBus = require("../eventBus.js");
const Form = require("../Form.js");
import { mixin as clickaway } from "vue-clickaway";
const ButtonClose = require("./ButtonClose.vue").default;
const Comments = require("./Comments.vue").default;

export default {
    components: {
        ButtonClose,
        Comments
    },
    mixins: [clickaway],
    props: {
        card: Object,
        listName: String,
        commentsList: Array
    },
    data() {
        return {
            updateForm: null,
            deleteForm: new Form(),

            mounted: false,
            titleUpdateTimer: null,
            descriptionUpdateTimer: null,

            descriptionActive: false
        };
    },
    computed: {
        comments() {
            return this.commentsList.filter(comment => {
                return comment.card_id.toString() === this.card.id.toString();
            });
        },
        descriptionHeight() {
            if (!this.mounted) return 0;

            const numberOfLines =
                this.updateForm.description === null ||
                this.updateForm.description === undefined
                    ? 0
                    : this.updateForm.description.split("\n").length;

            return numberOfLines * 24 + 24 + "px";
        }
    },
    beforeMount() {
        this.updateForm = new Form({
            name: this.card.name,
            description: this.card.description
        });

        setTimeout(() => (this.mounted = true));
    },
    watch: {
        ["updateForm.name"]() {
            if (!this.mounted) return;

            clearTimeout(this.titleUpdateTimer);

            this.titleUpdateTimer = setTimeout(this.handleSubmit, 500);
        },
        ["updateForm.description"]() {
            if (!this.mounted) return;

            clearTimeout(this.descriptionUpdateTimer);

            this.descriptionUpdateTimer = setTimeout(this.handleSubmit, 500);
        }
    },
    methods: {
        showDescription() {
            this.descriptionActive = true;
        },
        hideDescription() {
            this.descriptionActive = false;
        },

        cancel() {
            this.updateForm.reset();
            this.close();
        },
        close() {
            eventBus.$emit("toggleModal", false);
        },
        handleSubmit() {
            this.updateForm
                .patch("/cards/" + this.card.id)
                .then(response => {
                    console.log("response", response);

                    eventBus.$emit("updateCard", response.data);
                    // this.close();
                })
                .catch(error => {
                    console.log("error", error);
                });
        },
        deleteCard() {
            if (confirm("Are you sure you want to delete this card?")) {
                this.deleteForm
                    .delete("/cards/" + this.card.id)
                    .then(response => {
                        console.log("response", response);
                        eventBus.$emit("deleteCard", this.card);
                        this.close();
                    })
                    .catch(error => {
                        console.log("error", error);
                    });
            }
        }
    }
};
</script>
