<template>
    <div style="height: 100%">
        <div class="task-container">
            <Draggable
                group="lists"
                handle=".card-header"
                ghost-class="is-ghost"
                draggable=".task"
                @end="taskDragEnd"
                v-model="sortedLists"
            >
                <List
                    v-for="(list, index) in sortedLists"
                    :key="index"
                    :list="list"
                    :card-drag-end="cardDragEnd"
                    :card-drag-start="cardDragStart"
                    :card-added-to-new-list="cardAddedToNewList"
                ></List>

                <AddList :board-id="boardId"></AddList>
            </Draggable>
        </div>

        <EditCardModal
            v-if="modalActive"
            :list-name="lists[modalCard.task_id].name"
            :card="modalCard"
        ></EditCardModal>
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

            cards: [],
            lists: {},
            lastRemoved: null,
            newList: null,
            startIndex: null,
            cardForm: new Form(["start_list", "end_list", "new_position"]),
            taskForm: new Form(["start_position", "end_position"])
        };
    },

    computed: {
        sortedLists: {
            get() {
                return Object.keys(this.lists)
                    .sort((a, b) => this.lists[a].order - this.lists[b].order)
                    .map(key => this.lists[key]);
            },
            set(newArray) {
                const newLists = JSON.parse(JSON.stringify(this.lists));

                newArray.forEach((list, index) => {
                    newLists[list.id].order = index.toString();
                });

                this.lists = newLists;
            }
        }
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
        initializeLists() {
            const lists = {};

            /*
                Structure
                =========

                lists = {
                    id: 26,
                    created_at: "2020-01-18 17:11:48",
                    updated_at: "2020-01-19 11:44:52",
                    board_id: "1",
                    name: "Callums newest list",
                    order: "0",
                    cards: []
                };

            */

            this.listData.forEach(list => {
                lists[list.id] = {
                    ...list,
                    cards: this.cardData
                        .filter(
                            card =>
                                card.task_id.toString() === list.id.toString()
                        )
                        .sort((a, b) => parseInt(a.order) - parseInt(b.order))
                };
            });

            this.lists = lists;
        },

        /*
            ===================
            List Update Methods
            ===================
        */

        createList(list) {
            this.lists = {
                ...this.lists,
                [list.id]: { ...list, cards: [] }
            };
        },
        deleteList(listId) {
            const newLists = JSON.parse(JSON.stringify(this.lists));
            delete newLists[listId];
            this.lists = newLists;
        },
        updateCard(updatedCard) {
            this.lists[updatedCard.task_id].cards = this.lists[
                updatedCard.task_id
            ].cards.map(card => {
                return card.id.toString() === updatedCard.id.toString()
                    ? updatedCard
                    : card;
            });
        },
        addCard(newCard, listId) {
            this.lists[listId].cards = [...this.lists[listId].cards, newCard];
        },
        deleteCard(selectedCard) {
            this.lists[selectedCard.task_id].cards = this.lists[
                selectedCard.task_id
            ].cards.filter(
                card => card.id.toString() !== selectedCard.id.toString()
            );
        },
        cardDragged(listId, newArray) {
            // Update Card Index
            this.lists[listId].cards = newArray.map((card, index) => ({
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
            this.startCard = this.lists[list.id].cards[e.oldIndex].id;
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

            this.cardForm.start_list = startList.id;
            this.cardForm.end_list = endList.id;
            this.cardForm.new_position = endPosition;

            this.cardForm
                .patch("/cards/order/" + cardID)
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log("error", error);
                });
        },

        /*
        =================
        Task Drag Methods
        =================
        */

        taskDragEnd(e) {
            const { oldIndex: start, newIndex: end } = e;

            if (start === end) return; // Card hasn't moved

            this.taskForm.start_position = start;
            this.taskForm.end_position = end;

            this.taskForm
                .patch("/tasks/" + this.boardId)
                .then(response => {
                    console.log("response", response);
                    eventBus.$emit("reorderTasks", start, end);
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
