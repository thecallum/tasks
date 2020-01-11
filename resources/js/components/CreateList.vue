<template>
    <div class="create-list">
        <form @submit="handleSubmit">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Name
                </label>
                <input
                    v-model="form.name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="name"
                    type="text"
                    placeholder="List Name"
                />
            </div>

            <button
                type="submit"
                class="font-bold py-2 px-4 rounded bg-green-500 text-white hover:bg-green-700"
            >
                Create List
            </button>
        </form>

        <ul>
            <li v-for="error in form.errors">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
const axios = require("axios");
const Form = require("../Form.js");
const eventBus = require("../eventBus.js");

export default {
    props: {
        boardId: String
    },
    data() {
        return {
            form: new Form(["name"])
        };
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();

            if (this.form.name == "") return;

            this.form
                .submit("POST", "/tasks/" + this.boardId)
                .then(response => {
                    console.log("response", response);
                    this.form.reset();
                    eventBus.$emit("createList", response.data);
                })
                .catch(error => {
                    console.log("error", error);
                });
        }
    }
};
</script>

<style lang="scss" scoped>
.create-list {
    margin-bottom: 30px;
}
</style>
