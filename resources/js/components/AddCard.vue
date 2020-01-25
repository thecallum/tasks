<template>
    <div v-on-clickaway="close">
        <div v-if="!active">
            <button class="add-card-button" @click="open">
                Add another card
            </button>
        </div>

        <div v-else class="add-card-save">
            <textarea
                class="textarea"
                placeholder="Enter a title for this card..."
                v-model="form.name"
                ref="input"
            ></textarea>

            <div class="add-card-options">
                <form @submit="handleSubmit">
                    <button class="button is-success" type="submit">
                        Add Card
                    </button>
                    <ButtonClose
                        :close="close"
                        class="add-card-cancel"
                    ></ButtonClose>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
const Form = require("../Form.js");
const ButtonClose = require("./ButtonClose.vue").default;
import { mixin as clickaway } from "vue-clickaway";

export default {
    mixins: [clickaway],
    components: {
        ButtonClose
    },
    props: {
        listId: Number
    },
    data() {
        return {
            active: false,
            form: new Form(["name"])
        };
    },
    methods: {
        open() {
            this.active = true;
            this.focusInput();
        },
        close() {
            this.active = false;
            this.form.reset();
        },
        handleSubmit(e) {
            e.preventDefault();

            this.form
                .post("/cards/" + this.listId)
                .then(response => {
                    console.log({ response });

                    this.form.reset();

                    const newCard = response.data;
                    this.$store.commit("addCard", {
                        newCard,
                        listId: this.listId
                    });
                    this.focus = false;
                })
                .catch(error => {
                    // console.log({ error });
                    console.log("error", error.response.data.error);
                });
        },
        focusInput() {
            setTimeout(() => {
                this.$refs.input.focus();
            });
        }
    }
};
</script>
