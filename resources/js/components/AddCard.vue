<template>
    <div :data-name="listName">
        <p>Add Card</p>

        <button v-if="!focus" class="button" @click="addCard">Add Card</button>

        <form v-if="focus" @submit="handleSubmit" :data-name="listName">
            <input
                type="text"
                placeholder="Name"
                v-model="form.name"
                class="input"
            />

            <button class="button" type="submit">
                Add Card
            </button>

            <ul>
                <li v-for="error in form.errors">{{ error[0] }}</li>
            </ul>
        </form>
    </div>
</template>

<script>
const axios = require("axios");
const Form = require("../Form.js");
const eventBus = require("../eventBus.js");

export default {
    props: {
        listName: String,
        listId: Number
    },
    data() {
        return {
            focus: false,
            form: new Form(["name"])
        };
    },
    methods: {
        addCard() {
            if (!this.focus) {
                this.focus = true;
                console.log("add card", this.focus);

                this.createClickOutEventListener();
            }
        },
        handleSubmit(e) {
            e.preventDefault();

            this.form
                .post("/cards/" + this.listId)
                .then(response => {
                    console.log({ response });

                    this.form.reset();

                    const newCard = response.data;
                    eventBus.$emit("addCard", newCard, this.listName);
                })
                .catch(error => {
                    console.log("error", error.response.data.error);
                });
        },
        createClickOutEventListener() {
            setTimeout(() =>
                window.addEventListener("click", this.clickOutEventHandler)
            );
        },
        removeClickOutEventListener() {
            window.removeEventListener("click", this.clickOutEventHandler);
        },
        clickOutEventHandler(e) {
            if (
                (e.target.dataset !== undefined &&
                    e.target.dataset.name === this.listName) ||
                (!!e.target.parentElement &&
                    e.target.parentElement.dataset !== undefined &&
                    e.target.parentElement.dataset.name === this.listName)
            ) {
                // Inside element, dont click out
                return;
            }

            // user clicked out
            this.removeClickOutEventListener();
            this.focus = false;
            this.form.reset();
        }
    }
};
</script>

<style scoped>
.button {
    width: 100%;
    border: none;
    background: #000;
    color: #fff;
    padding: 15px;
}

.input {
    border: 1px solid #333;
    border-radius: 3px;
    margin-bottom: 15px;
    width: 100%;
}
</style>
