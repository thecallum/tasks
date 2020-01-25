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
const Form = require("../Form.js");

export default {
    mixins: [clickaway],
    data() {
        return {
            form: new Form()
        };
    },
    methods: {
        exit() {
            // alert("exit");
            this.close();
        },
        deleteList() {
            if (confirm("Do you want to delete the list?")) {
                this.form
                    .delete("/tasks/" + this.list.id)
                    .then(response => {
                        console.log("response", response);
                        this.$store.commit("deleteList", { list: this.list });
                        this.close();
                    })
                    .catch(error => {
                        console.log("error", error);
                    });
            }
        }
    },
    props: {
        close: Function,
        list: Object
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
