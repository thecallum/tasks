<template>
    <div class="list-item card">
        <span>{{ card.name }} | {{ card.order }}</span>
        <div>
            <button class="button-edit" @click="editCard">Edit</button>
            <button class="button-delete" @click="deleteCard"></button>
        </div>

        <div v-if="toggled" class="card-details">
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
                    <button type="submit">Update Card</button>
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
            this.deleteForm
                .delete("/cards/" + this.card.id)
                .then(response => {
                    console.log("response", response);
                    eventBus.$emit("deleteCard", this.card, this.listName);
                })
                .catch(error => {
                    console.log("error", error);
                });
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

<style scoped lang="scss">
.card {
    padding: 15px 30px 15px 15px;
    position: relative;

    &-details {
        margin-top: 15px;
        background: rgba(255, 255, 255, 0.2);
    }
}

.button-delete {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);

    background: hsl(0, 50%, 60%);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;

    &:hover {
        background: hsl(0, 50%, 40%);
    }

    &::after {
        content: "X";
        position: absolute;
        display: block;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        vertical-align: center;
    }
}

.button-edit {
    background: hsl(150, 80%, 30%);
    padding: 3px 6px;
    border-radius: 4px;

    &:hover {
        background: hsl(150, 80%, 40%);
    }
}
</style>
