<template>
    <div
        class="list-item"
        style="padding: 30px;"
    >
    {{ card.name }}
    </div>
</template>

<script>
export default {
    props: {
        card: Object
    }
};
</script>
