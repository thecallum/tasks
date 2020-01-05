<template>
    <div class="list-col" :class="{ empty: list.length == 0 }">
        <Draggable
            :list="list"
            group="group"
            @end="end"
            animation="200"
            ghost-class="ghost"
            draggable=".list-item"
        >
            <div
                :class="['list-item', classname]"
                v-for="card in list"
                :key="card.value"
                style="padding: 30px;"
            >
                {{ card.value }}
            </div>
        </Draggable>

        <button
            style="width: 100%; border: none; background: #000; color: #fff; padding: 15px;"
            :style="{ 'margin-top': margin }"
        >
            Add Card
        </button>
    </div>
</template>

<script>
const Draggable = require("vuedraggable");

export default {
    components: {
        Draggable
    },
    props: {
        list: Array,
        group: String,
        end: Function,
        classname: String
    },

    data() {
        return {
            margin: this.list.length > 0 ? "15px" : "0"
        };
    }
};
</script>
