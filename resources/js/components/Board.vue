<template>
    <div style="height: 100%">
        <div class="task-container">
            <Draggable
                group="lists"
                handle=".card-header"
                ghost-class="is-ghost"
                draggable=".task"
            >
                <List
                    v-for="(list, key) in cards"
                    :list="lists[key]"
                    :cards="list"
                    :card-drag-end="cardDragEnd"
                    :card-drag-start="cardDragStart"
                    :card-added-to-new-list="cardAddedToNewList"
                ></List>

                <AddList :board-id="boardId"></AddList>
            </Draggable>
        </div>

        <EditCardModal v-if="modalActive" :card="modalCard"></EditCardModal>
    </div>
</template>

<script>
const Draggable = require("vuedraggable");
const List = require("./List.vue").default;
const Form = require("../Form");
const AddList = require("./AddList.vue").default;
const EditCardModal = require("./EditCardModal.vue").default;
const eventBus = require("../eventBus.js");

export default {
    beforeMount() {
        this.initializeCards();
        this.initializeLists();
        this.initializeEventHandlers();

        console.table(this.listData);
    },
    props: {
        listData: Array,
        cardData: Array,
        boardId: String
    },
    components: {
        List,
        EditCardModal,
        AddList,
        EditCardModal,
        Draggable
    },
    data() {
        return {
            modalActive: false,
            modalCard: {},

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
            eventBus.$on("cardDragged", this.cardDragged);

            eventBus.$on("toggleModal", this.toggleModal);
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
            const newCards = JSON.parse(JSON.stringify(this.cards));
            delete newCards[list.name];
            this.cards = newCards;
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
        cardDragged(listName, newArray) {
            // Update Card Index
            this.cards[listName] = newArray.map((card, index) => ({
                ...card,
                order: index
            }));
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
        cardDragStart(e, list) {
            // reset new list
            this.newList = null;
            this.startIndex = e.oldIndex;
            this.startCard = this.cards[list.name][e.oldIndex].id;
        },
        cardDragEnd(e, list) {
            const { newIndex } = e;
            const endList = this.newList === null ? list : this.newList;
            const cardID = this.startCard;

            this.handleCardDrag(
                cardID,
                this.startIndex,
                newIndex,
                list,
                endList
            );
        },
        handleCardDrag(cardID, startPosition, endPosition, startList, endList) {
            if (startPosition === endPosition && startList.id === endList.id)
                return; /* Card has not moved */

            this.form.start_list = startList.id;
            this.form.end_list = endList.id;
            this.form.new_position = endPosition;

            this.form
                .patch("/cards/order/" + cardID)
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log("error", error);
                });
        },

        /*
        =============
        Modal methods
        =============
        */

        toggleModal(showModal, card) {
            if (showModal) {
                this.modalCard = card;
                this.modalActive = true;
            } else {
                this.modalActive = false;
                this.modalCard = {};
            }
        }
    }
};
</script>
