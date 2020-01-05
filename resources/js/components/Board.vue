<template>
    <div>
        <p v-if="updating.loading || updating.message">
            {{ updating.loading ? "loading" : updating.message }}
        </p>

        <div class="list-container">
            <List
                :list="list(key)"
                group="list"
                :end="end"
                :classname="key"
                v-for="(key, index) in Object.keys(cards)"
            ></List>
        </div>
    </div>
</template>

<script>
const List = require("./List.vue").default;

let ID = 0;

function Card(value, order, listID) {
    this.value = value;
    this.order = order;
    this.listID = listID;
    this.id = ID++;
}

export default {
    mounted() {
        console.log("Board mounted");
    },
    components: {
        List
    },
    data() {
        return {
            cards: {
                first: [new Card("Item 1", 1, 1), new Card("Item 2", 2, 1)],
                second: [
                    new Card("Item 3", 1, 2),
                    new Card("Item 4", 2, 2),
                    new Card("Item 5", 3, 2),
                    new Card("Item 6", 4, 2),
                    new Card("Item 7", 5, 2),
                    new Card("Item 8", 6, 2)
                ],
                third: [
                    new Card("Item 9", 1, 3),
                    new Card("Item 10", 2, 3),
                    new Card("Item 11", 3, 3)
                ],
                fourth: [
                    new Card("Item 12", 1, 4),
                    new Card("Item 13", 2, 4),
                    new Card("Item 14", 3, 4)
                ]
            },

            lastRemoved: null,
            updating: {
                message: null,
                loading: false
            }
        };
    },

    methods: {
        end(e) {
            this.updateList();
        },

        updateList(listID, cardIndex) {
            const toUpdate = [];
            // Resort List
            const newList = JSON.parse(JSON.stringify(this.cards));
            const oldList = JSON.parse(JSON.stringify(this.cards));

            // Update ListID of moved card

            // Update order
            Object.keys(this.cards).forEach((key, listIndex) => {
                newList[key] = newList[key].map((card, index) => {
                    const newCard = {
                        ...card,
                        order: index + 1,
                        listID: listIndex + 1
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

        list(key) {
            return this.cards[key];
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
