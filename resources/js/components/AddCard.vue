<template>
    <div :data-name="listName">
        <div v-if="!focus">
            <button class="add-card-button" @click="addCard">
                Add another card
            </button>
        </div>

        <div v-else class="add-card-save">
            <textarea
                class="textarea"
                placeholder="Enter a title for this card..."
                v-model="form.name"
            ></textarea>

            <div class="add-card-options">
                <form @submit="handleSubmit">
                    <button class="button is-success" type="submit">
                        Add Card
                    </button>
                    <button
                        type="button"
                        class="add-card-cancel"
                        @click="cancel"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
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
                // this.createClickOutEventListener();
            }
        },
        cancel() {
            this.focus = false;
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
                    eventBus.$emit("addCard", newCard, this.listName);
                    this.focus = false;
                })
                .catch(error => {
                    // console.log({ error });
                    console.log("error", error.response.data.error);
                });
        }
        // createClickOutEventListener() {
        //     setTimeout(() =>
        //         window.addEventListener("click", this.clickOutEventHandler)
        //     );
        // },
        // removeClickOutEventListener() {
        //     window.removeEventListener("click", this.clickOutEventHandler);
        // },
        // clickOutEventHandler(e) {
        //     if (
        //         (e.target.dataset !== undefined &&
        //             e.target.dataset.name === this.listName) ||
        //         (!!e.target.parentElement &&
        //             e.target.parentElement.dataset !== undefined &&
        //             e.target.parentElement.dataset.name === this.listName)
        //     ) {
        //         // Inside element, dont click out
        //         return;
        //     }

        //     // user clicked out
        //     this.removeClickOutEventListener();
        //     this.focus = false;
        //     this.form.reset();
        // }
    }
};
</script>
