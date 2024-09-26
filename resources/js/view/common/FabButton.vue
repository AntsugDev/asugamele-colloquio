<template>
    <v-fab
        :key="icon"
        :color="icon === 'mdi-account-details' ? 'success' : 'primary'"
        :icon="icon"
        class="ms-4 mb-4"
        location="top end"
        size="64"
        absolute
        app
        appear
        @click="goTo()"
    ></v-fab>
</template>
<script setup>
import {useRoute, useRouter} from "vue-router";
import {computed} from "vue";
const router = useRouter();
const route = useRoute();

const icon = computed(() =>{
    if(route.name === 'User')
        return "mdi-table";
    else
        return "mdi-account-details"
})
const goTo = () => {
    if(route.name === 'User')
        router.push({name:'Table'})
    else
        router.push({name:'User'})

}
</script>
<style >
.v-fab--right .v-fab__container, .v-fab--end .v-fab__container {
    right: 23px!important;
}
</style>
