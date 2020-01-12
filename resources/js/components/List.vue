<template>
    <div>
        <div class="list" :class="{ empty: cards.length == 0 }">
            <h1>{{ list.name }}</h1>

            <div class="delete-list" @click="deleteList"></div>

            <Draggable
                :cards="cards"
                :group="group"
                @end="e => cardDragEnd(e, list)"
                @start="e => cardDragStart(e)"
                @add="e => cardAddedToNewList(e, list)"
                animation="200"
                ghost-class="ghost"
                draggable=".list-item"
            >
                <Card
                    v-for="card in cards"
                    :key="card.value"
                    :card="card"
                    :list-name="list.name"
                ></Card>
            </Draggable>

            <add-card :list-name="list.name" :list-id="list.id"></add-card>
        </div>
    </div>
</template>

<script>
const Draggable = require("vuedraggable");
const AddCard = require("./AddCard.vue").default;
const Card = require("./Card.vue").default;
const Form = require("../Form.js");
const eventBus = require("../eventBus.js");

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
        cardDragEnd: Function,
        cardDragStart: Function,
        cardAddedToNewList: Function
    },
    data() {
        return {
            form: new Form([])
        };
    },
    methods: {
        deleteList() {
            if (confirm("Do you want to delete the list?")) {
                this.form
                    .delete("/tasks/" + this.list.id)
                    .then(response => {
                        console.log("response", response);
                        eventBus.$emit("deleteList", this.list);
                    })
                    .catch(error => {
                        console.log("error", error);
                    });
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.list {
    padding: 60px 30px 30px;
    background: hsl(200, 50%, 50%);
    margin-left: 30px;
    position: relative;
    width: 300px;
}

.delete-list {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: hsl(0, 50%, 50%);
    cursor: pointer;
}
</style>
