<template>
    <div>
        <div class="popper" v-on-clickaway="exit">
            <aside class="menu">
                <p class="menu-label">
                    General
                </p>
                <ul class="menu-list">
                    <li @click="deleteList"><a>Delete</a></li>
                    <li><a>Customers</a></li>
                </ul>
            </aside>
        </div>
    </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
    mixins: [clickaway],
    methods: {
        exit() {
            // alert("exit");
            this.close();
        }
    },
    props: {
        close: Function,
        deleteList: Function
    }
};
</script>

<style lang="scss" scoped>
.popper {
    position: absolute;
    top: 40px;
    right: -40px;
    z-index: 100;

    padding: 15px;

    background: #eee;

    &-exit {
        position: absolute;
        width: 1000vw;
        height: 1000vh;
        background: transparent;
        left: 0;
        top: 0;
        transform: translate(-50%, -50%);
        z-index: 80;
    }
}
</style>
