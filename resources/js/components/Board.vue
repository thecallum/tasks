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

export default {
    props: {
        listData: Array,
        cardData: Array
    },
    mounted() {
        console.log("Board mounted");

        const lists = this.listData;

        const sortedList = {};

        lists.forEach(list => {
            const { name, order,id } = list;
            // console.log('list', name, id);

            const cards = this.cardData.filter(card => card.task_id.toString() === id.toString());

            // console.log({cards})

            sortedList[name] = cards;
        });

        // console.log({sortedList})

        //  console.log(sortedList)
        // console.log(this.cards)

    this.cards = sortedList;

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
        end(e) {
            this.updateList();
        },

        updateList(list_id, cardIndex) {
            const toUpdate = [];
            // Resort List
            const newList = JSON.parse(JSON.stringify(this.cards));
            const oldList = JSON.parse(JSON.stringify(this.cards));

            // Update lsit_id of moved card

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
