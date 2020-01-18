<template>
    <div class="card task has-background-white-ter">
        <header class="card-header">
            <p class="card-header-title">{{ list.name }}</p>

            <div @click="openPopper" class="card-header-icon yee">
                <span class="icon">
                    <i class="fas fa-ellipsis-h"></i>
                </span>

                <PopoverMenu
                    :delete-list="deleteList"
                    :close="closePopper"
                    v-if="showPopper"
                ></PopoverMenu>
            </div>
        </header>

        <div class="card-content">
            <Draggable
                :group="group"
                @end="e => cardDragEnd(e, list)"
                @start="e => cardDragStart(e, list)"
                @add="e => cardAddedToNewList(e, list)"
                animation="200"
                draggable=".list-card"
                v-model="localCards"
            >
                <Card
                    v-for="card in localCards"
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
const PopoverMenu = require("./PopoverMenu.vue").default;
const Form = require("../Form.js");
const eventBus = require("../eventBus.js");

export default {
    components: {
        Draggable,
        AddCard,
        Card,
        PopoverMenu
    },
    props: {
        list: Object,
        cards: Array,
        group: String,
        cardDragEnd: Function,
        cardDragStart: Function,
        cardAddedToNewList: Function
    },
    computed: {
        localCards: {
            get() {
                return this.cards;
            },
            set(newArray) {
                eventBus.$emit("cardDragged", this.list.name, newArray);
            }
        }
    },
    data() {
        return {
            form: new Form(),
            showPopper: false
        };
    },
    mounted() {
        // tippy('.yee', {
    },
    methods: {
        closePopper() {
            this.showPopper = false;
        },
        openPopper() {
            this.showPopper = true;
        },
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
