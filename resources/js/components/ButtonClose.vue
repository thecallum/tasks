<template>
    <button type="button" class="button-close" @click="close">
        <i class="fas fa-times"></i>
    </button>
</template>

<script>
export default {
    props: {
        close: Function
    }
};
</script>
