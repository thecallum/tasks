<template>
    <div>
        <form @submit="handleSubmit">
            <div>
                <label>Name</label>
                <input
                    v-model="form.name"
                    name="name"
                    type="text"
                    placeholder="List Name"
                />
            </div>

            <button type="submit">Create List</button>
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
                .post("/tasks/" + this.boardId)
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
