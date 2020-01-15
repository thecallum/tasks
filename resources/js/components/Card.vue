<template>
    <div class="list-card box">
        <div class="list-card-body">
            <span>{{ card.name }}</span>

            <span>
                <button class="button" @click="editCard">Edit</button>
                <button class="button is-danger" @click="deleteCard">
                    Delete
                </button>
            </span>
        </div>

        <div class="list-card-edit" v-if="toggled">
            <h3>Card Details</h3>

            <form @submit="handleSubmit">
                <div>
                    <input
                        type="text"
                        placeholder="Card Name"
                        v-model="updateForm.name"
                    />
                </div>

                <div>
                    <input
                        type="text"
                        placeholder="Card Description"
                        v-model="updateForm.description"
                    />
                </div>

                <ul>
                    <li v-for="error in updateForm.errors">{{ error[0] }}</li>
                </ul>

                <div>
                    <button class="button is-primary" type="submit">
                        Update Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
const eventBus = require("../eventBus.js");
const Form = require("../Form.js");

export default {
    props: {
        card: Object,
        listName: String
    },
    data() {
        return {
            deleteForm: new Form(),
            updateForm: null,
            toggled: false
        };
    },
    watch: {
        toggled(isToggled) {
            /* Initialize update form when opened */
            if (isToggled) {
                this.updateForm = new Form({
                    name: this.card.name,
                    description: this.card.description
                });
            } else {
                this.updateForm = null;
            }
        }
    },
    methods: {
        updateFormFields() {
            this.updateForm.name = this.card.name;
            this.updateForm.description = this.card.description;
        },
        editCard() {
            this.toggled = !this.toggled;
        },
        deleteCard() {
            if (confirm("Ar you sure you want to delete this card?")) {
                this.deleteForm
                    .delete("/cards/" + this.card.id)
                    .then(response => {
                        console.log("response", response);
                        eventBus.$emit("deleteCard", this.card, this.listName);
                    })
                    .catch(error => {
                        console.log("error", error);
                    });
            }
        },

        handleSubmit(e) {
            e.preventDefault();

            console.log("Card update submit");

            this.updateForm
                .patch("/cards/" + this.card.id)
                .then(response => {
                    console.log("response", response);
                    this.toggled = false;
                    eventBus.$emit("updateCard", response.data, this.listName);
                })
                .catch(error => {
                    console.log("error", error);
                });

            console.log({
                ...this.updateForm
            });
        }
    }
};
</script>
