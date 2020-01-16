<template>
    <div class="list-card box" @click="editCard">
        <div class="list-card-body">
            <span>{{ card.name }}</span>

            <i class="fas fa-pen list-card-icon"></i>
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
