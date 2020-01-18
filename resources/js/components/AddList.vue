<template>
    <div class="add-list" v-on-clickaway="close">
        <button @click="open" v-if="!active" class="add-list-button">
            Add Another List
        </button>
        <div v-else class="card has-background-white-ter add-list-save">
            <form @submit="handleSubmit">
                <input
                    type="text"
                    class="input"
                    placeholder="Enter list title..."
                    ref="input"
                    v-model="form.name"
                />

                <ul>
                    <li v-for="error in form.errors">{{ error[0] }}</li>
                </ul>

                <div class="add-list-options ">
                    <button class="button is-success" type="submit">
                        Add List
                    </button>

                    <ButtonClose
                        class="add-list-close"
                        :close="close"
                    ></ButtonClose>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
const Form = require("../Form.js");
import { mixin as clickaway } from "vue-clickaway";
const ButtonClose = require("./ButtonClose.vue").default;
const eventBus = require("../eventBus.js");

export default {
    mixins: [clickaway],
    components: {
        ButtonClose
    },
    props: {
        boardId: String
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

            console.log("submit");

            if (this.form.name == "") return;

            this.form
                .post("/tasks/" + this.boardId)
                .then(response => {
                    console.log("response", response);
                    this.form.reset();
                    eventBus.$emit("createList", response.data);
                    this.close();
                })
                .catch(error => {
                    console.log("error", error);
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
