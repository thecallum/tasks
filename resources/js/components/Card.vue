<template>
    <div class="list-card box">
        <div class="list-card-body">
            <span>{{ card.name }}</span>

            <span>
                <button class="button" @click="editCard">Edit</button>
            </span>
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
    methods: {
        editCard() {
            eventBus.$emit("toggleModal", true, {
                ...this.card,
                listName: this.listName
            });
        }
    }
};
</script>
