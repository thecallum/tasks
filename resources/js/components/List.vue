<template>
    <div class="list-col" :class="{ empty: cards.length == 0 }">
        <Draggable
            :cards="cards"
            :group="group"
            @end="end"
            animation="200"
            ghost-class="ghost"
            draggable=".list-item"
        >
            <Card v-for="card in cards" :key="card.value" :card="card"></Card>
        </Draggable>

        <add-card :list-name="list.name" :list-id="list.id"></add-card>
    </div>
</template>

<script>
const Draggable = require("vuedraggable");
const AddCard = require("./AddCard.vue").default;
const Card = require("./Card.vue").default;

export default {
    components: {
        Draggable,
        AddCard,
        Card
    },
    props: {
        list: Object,
        cards: Array,
        group: String,
        end: Function
    }
};
</script>
