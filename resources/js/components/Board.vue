<template>
    <div>
        <p v-if="updating.loading || updating.message">
            {{ updating.loading ? "loading" : updating.message }}
        </p>

        <div class="list-container">
            <List
                :list="list"
                :cards="cards[list.name]"
                group="list"
                :end="end"
                v-for="(list, index) in listData"
            ></List>
        </div>
    </div>
</template>

<script>
const List = require("./List.vue").default;
const eventBus = require('../eventBus.js');

export default {
    beforeMount() {
        console.log("Board mounted");
        this.initializeCards();

        // Global Add Card Event
        eventBus.$on('addCard', this.addCard);
        eventBus.$on('deleteCard', this.deleteCard);
    },
    props: {
        listData: Array,
        cardData: Array
    },
    components: {
        List
    },

    
    data() {
        return {
            cards: {},

            lastRemoved: null,
            updating: {
                message: null,
                loading: false
            }
        };
    },

    methods: {
        addCard(card) {
            const currentList = this.listData.filter(list => list.id.toString() === card.task_id.toString())[0];
            const listName = currentList.name;

            this.cards = {
                ...this.cards,
                [listName]: [ ...this.cards[listName], card ]
            };
        },
        deleteCard(selectedCard) {
            console.log('delete CARD', selectedCard);

            const currentList = this.listData.filter(list => list.id.toString() === selectedCard.task_id.toString())[0];
            const listName = currentList.name;

            const updatedCards = {
                ...this.cards,
                [listName]: this.cards[listName].filter(card => card.id.toString() !== selectedCard.id.toString())
            };

            // Cannot use order yet, all cards have order of 1
            this.cards = this.reorderCards(updatedCards);
            this.cards = updatedCards;
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
