<template>
    <div class="card task has-background-white-ter">
        <header class="card-header">
            <p class="card-header-title">{{ list.name }}</p>

            <div @click="openMenu" class="card-header-icon yee">
                <span class="icon">
                    <i class="fas fa-ellipsis-h"></i>
                </span>

                <ListMenu
                    :list="list"
                    :close="closeMenu"
                    v-if="showMenu"
                ></ListMenu>
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
const ListMenu = require("./ListMenu.vue").default;

export default {
    components: {
        Draggable,
        AddCard,
        Card,
        ListMenu
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
            showMenu: false
        };
    },
    methods: {
        openMenu() {
            this.showMenu = true;
        },
        closeMenu() {
            this.showMenu = false;
        }
    }
};
</script>
