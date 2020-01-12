<template>
    <div>
        <create-list :board-id="boardId"></create-list>

        <div class="list-container">
            <List
                v-for="(list, key) in cards"
                :list="lists[key]"
                :cards="list"
                group="list"
                :card-drag-end="cardDragEnd"
                :card-drag-start="cardDragStart"
                :card-added-to-new-list="cardAddedToNewList"
            ></List>
        </div>
    </div>
</template>

<script>
const List = require("./List.vue").default;
const Form = require("../Form");
const CreateList = require("./CreateList.vue").default;
const eventBus = require("../eventBus.js");

export default {
    beforeMount() {
        this.initializeCards();
        this.initializeLists();
        this.initializeEventHandlers();
    },
    props: {
        listData: Array,
        cardData: Array,
        boardId: String
    },
    components: {
        List,
        CreateList
    },
    data() {
        return {
            cards: {},
            lists: {},
            lastRemoved: null,
            newList: null,
            startIndex: null,
            form: new Form(["start_list", "end_list", "new_position"])
        };
    },

    methods: {
        /* 
            =============
            Setup Methods 
            =============
        */

        initializeEventHandlers() {
            // Global Add Card Event
            eventBus.$on("addCard", this.addCard);
            eventBus.$on("deleteCard", this.deleteCard);
            eventBus.$on("updateCard", this.updateCard);
            eventBus.$on("deleteList", this.deleteList);
            eventBus.$on("createList", this.createList);
        },
        initializeCards() {
            const lists = this.listData;
            const cards = {};

            lists.forEach(({ name, id }) => {
                const listCards = this.cardData
                    .filter(card => card.task_id.toString() === id.toString())
                    .sort((a, b) => parseInt(a.order) - parseInt(b.order));
                cards[name] = listCards;
            });
            this.cards = cards;
        },
        initializeLists() {
            this.listData.map(list => {
                this.lists[list.name] = list;
            });
        },

        /* 
            ===================
            List Update Methods 
            ===================
        */

        createList(list) {
            // update lists
            this.lists = { ...this.lists, [list.name]: list };
            // update cards
            this.cards = { ...this.cards, [list.name]: [] };
        },
        deleteList(list) {
            delete this.cards[list.name];
        },
        updateCard(updatedCard, listName) {
            this.cards[listName] = this.cards[listName].map(card => {
                return card.id.toString() === updatedCard.id.toString()
                    ? updatedCard
                    : card;
            });
        },
        addCard(card, listName) {
            this.cards[listName] = [...this.cards[listName], card];
        },
        deleteCard(selectedCard, listName) {
            this.cards[listName] = this.cards[listName].filter(
                card => card.id.toString() !== selectedCard.id.toString()
            );
        },

        /* 
            ===========================
            Drag Event Methods 
            (Update database on change) 
            ===========================
        */

        cardAddedToNewList(e, list) {
            this.newList = list;
        },
        cardDragStart(e) {
            // reset new list
            this.newList = null;
            this.startIndex = e.oldIndex;
        },
        cardDragEnd(e, list) {
            const { newIndex } = e;
            const endList = this.newList === null ? list.id : this.newList.id;
            const card = this.cards[list.name][this.startIndex];

            if (this.startIndex === newIndex && list.id === endList)
                return; /* Card must have moved */

            this.form.start_list = list.id;
            this.form.end_list = endList;
            this.form.new_position = newIndex;

            this.form
                .patch("/cards/order/" + card.id)
                .then(response => {
                    console.table(response.data);
                })
                .catch(error => {
                    console.log("error", error);
                });
        }
    }
};
</script>

<style lang="scss" scoped>
.list-container {
    margin-left: -30px !important;
    padding: 0 !important;
}
</style>
