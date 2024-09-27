<template>
    <router-view></router-view>
    <FabButton></FabButton>
</template>
<script setup>
import FabButton from "./common/FabButton.vue";
import DialogAlert from "./common/DialogAlert.vue";
import {useStore} from "vuex";
import {computed} from "vue";

const store = useStore();
const dialog =computed(() => {
    return store.getters['Dialog/getDialog']
})

</script>
<style scoped lang="css">

</style>
