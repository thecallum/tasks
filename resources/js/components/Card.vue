<template>
    <div class="list-item card">
        <span>{{ card.name }} | {{ card.order}}</span>
        <button class="button-delete" @click="deleteCard"></button>
    </div>
</template>

<script>
const eventBus = require("../eventBus.js");
const Form = require("../Form.js");

export default {
    props: {
        card: Object
    },
    data() {
        return {
            form: new Form([])
        }
    },
    methods: {
        deleteCard() {
            this.form.submit('DELETE', '/cards/' + this.card.id)
            .then(response => {
                console.log('response', response);
                eventBus.$emit("deleteCard", this.card);

            })
            .catch(err => {
                console.log('error', error)
            })
        }
    }
};
</script>

<style scoped>
.card {
    padding: 15px 30px 15px 15px;
    position: relative;
}

.button-delete {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);

    background: hsl(0, 50%, 60%);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
}

.button-delete:hover {
    background: hsl(0, 50%, 40%);
}

.button-delete::after {
    content: "X";
    position: absolute;
    display: block;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    vertical-align: center;
}
</style>
