<template>
    <div class="modal is-active">
        <div class="modal-background" @click="close"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ card.name }}</p>
                <button
                    class="delete"
                    aria-label="close"
                    @click="close"
                ></button>
            </header>
            <section class="modal-card-body">
                <!-- Content ... -->

                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input
                            class="input"
                            type="text"
                            placeholder="Card Name"
                            v-model="updateForm.name"
                        />
                    </div>
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <input
                            class="input"
                            type="text"
                            placeholder="Card Description"
                            v-model="updateForm.description"
                        />
                    </div>
                </div>

                <div>
                    <button
                        class="button is-danger"
                        type="button"
                        @click="deleteCard"
                    >
                        Delete Card
                    </button>
                </div>

                <ul>
                    <li
                        v-for="error in updateForm.errors"
                        class="has-text-danger"
                    >
                        {{ error[0] }}
                    </li>
                </ul>
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

export default {
    props: {
        card: Object
    },
    data() {
        return {
            updateForm: null,
            deleteForm: new Form()
        };
    },
    beforeMount() {
        this.updateForm = new Form({
            name: this.card.name,
            description: this.card.description
        });
    },
    methods: {
        cancel() {
            this.updateForm.reset();
            this.close();
        },
        close() {
            eventBus.$emit("toggleModal", false);
        },
        handleSubmit(e) {
            e.preventDefault();

            console.log("Card update submit");

            this.updateForm
                .patch("/cards/" + this.card.id)
                .then(response => {
                    console.log("response", response);

                    eventBus.$emit("updateCard", response.data);
                    this.close();
                })
                .catch(error => {
                    console.log("error", error);
                });

            console.log({
                ...this.updateForm
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
