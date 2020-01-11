<template>
    <div>
        <p v-if="updating.loading || updating.message">
            {{ updating.loading ? "loading" : updating.message }}
        </p>

        <create-list :board-id="boardId"></create-list>

        <div class="list-container">
            <List
                :list="lists[key]"
                :cards="list"
                group="list"
                :end="end"
                v-for="(list, key) in cards"
            ></List>
        </div>
    </div>
</template>

<script>
const List = require("./List.vue").default;
const CreateList = require("./CreateList.vue").default;
const eventBus = require("../eventBus.js");

export default {
    beforeMount() {
        console.log("Board mounted");

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
            updating: {
                message: null,
                loading: false
            }
        };
    },

    methods: {
        createList(list) {
            // update lists
            this.lists = {
                ...this.lists,
                [list.name]: list
            };

            // update cards
            this.cards = {
                ...this.cards,
                [list.name]: []
            };
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

        reorderCards(cards) {
            // const oldList = JSON.parse(JSON.stringify(cards));
            // const newList = JSON.parse(JSON.stringify(cards));
            // const toUpdate = [];
            // console.log('updatedcards', cards)
            // // Update order
            // Object.keys(oldList).forEach((key, listIndex) => {
            //     // console.log('reorder', key)
            //     newList[key] = newList[key].map((card, index) => {
            //         const newCard = {
            //             ...card,
            //             order: index + 1,
            //             list_id: listIndex + 1
            //         };
            //         if (this.cardUpdated(newCard, oldList[key][index]))
            //             toUpdate.push(newCard);
            //         return newCard;
            //     });
            // });
            // console.log('toUpdate', toUpdate)
        },

        initializeCards() {
            const lists = this.listData;
            const cards = {};

            lists.forEach(({ name, id }) => {
                const listCards = this.cardData.filter(
                    card => card.task_id.toString() === id.toString()
                );
                cards[name] = listCards;
            });

            this.cards = cards;
        },

        initializeLists() {
            this.listData.map(list => {
                this.lists[list.name] = list;
            });
        },

        initializeEventHandlers() {
            // Global Add Card Event
            eventBus.$on("addCard", this.addCard);
            eventBus.$on("deleteCard", this.deleteCard);
            eventBus.$on("updateCard", this.updateCard);
            eventBus.$on("deleteList", this.deleteList);
            eventBus.$on("createList", this.createList);
        },

        end(e) {
            this.updateList();
        },

        // when list reordered
        updateList(list_id, cardIndex) {
            const toUpdate = [];
            // Resort List
            const newList = JSON.parse(JSON.stringify(this.cards));
            const oldList = JSON.parse(JSON.stringify(this.cards));

            // Update list_id of moved card

            // Update order
            Object.keys(this.cards).forEach((key, listIndex) => {
                newList[key] = newList[key].map((card, index) => {
                    const newCard = {
                        ...card,
                        order: index + 1,
                        list_id: listIndex + 1
                    };
                    if (this.cardUpdated(newCard, oldList[key][index]))
                        toUpdate.push(newCard);
                    return newCard;
                });
            });

            this.pushUpdate(toUpdate);
            this.cards = newList;
        },

        cardUpdated(newCard, oldCard) {
            return JSON.stringify(newCard) !== JSON.stringify(oldCard);
        },

        pushUpdate(toUpdate) {
            if (toUpdate.length < 1) return;

            this.updating.message = null;
            this.updating.loading = true;

            setTimeout(() => {
                this.updating.message = "updated";
                this.updating.loading = false;
            }, 1000);
            console.log("push update");
            console.table(JSON.parse(JSON.stringify(toUpdate)));
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
