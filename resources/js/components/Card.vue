<template>
    <div class="list-card box" @click="editCard">
        <div class="list-card-body">
            <span>
                <div>{{ card.name }}</div>
                <i
                    class="fa fa-align-left list-card-desc"
                    aria-hidden="true"
                    v-if="card.description"
                ></i>
            </span>
            <i class="fas fa-pen list-card-icon"></i>
        </div>
    </div>
</template>

<script>
const Form = require("../Form.js");

export default {
    props: {
        card: Object,
        listName: String
    },
    methods: {
        editCard() {
            this.$store.commit("toggleModal", {
                showModal: true,
                card: {
                    ...this.card,
                    listName: this.listName
                }
            });
        }
    }
};
</script>
