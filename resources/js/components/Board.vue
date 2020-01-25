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
            :comments-list="comments"
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

export default {
    props: {
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
            lastRemoved: null,
            newList: null,
            startIndex: null,
            cardForm: new Form(["start_list", "end_list", "new_position"]),
            taskForm: new Form(["start_position", "end_position"])
        };
    },

    computed: {
        modalActive() {
            return this.$store.state.modalActive;
        },
        modalCard() {
            return this.$store.state.modalCard;
        },
        comments() {
            return this.$store.state.comments;
        },

        lists() {
            return this.$store.state.lists;
        },

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

                this.$store.commit("updateLists", { lists: newLists });
            }
        }
    },

    methods: {
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
                })
                .catch(error => {
                    console.log("error", error);
                });
        }
    }
};
</script>
