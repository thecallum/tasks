<template>
    <div class="list-col" :class="{ empty: list.length == 0 }">
        <Draggable
            :list="list"
            group="group"
            @end="end"
            animation="200"
            ghost-class="ghost"
            draggable=".list-item"
        >
            <Card v-for="card in list" :key="card.value" :card="card"></Card>
        </Draggable>

        <add-card :list-name="listName" :list-id="listId"></add-card>

        
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
        listName: String,
        listId: Number, 
        list: Array,
        group: String,
        end: Function
    },
    mounted() {
        console.log('name', this.listName)
    }
};
</script>
