<template>
    <v-layout>
        <v-main class="d-flex justify-center">
            <router-view></router-view>
        </v-main>
    </v-layout>
</template>
<script setup>
import {useStore} from "vuex";
import {computed} from "vue";
import {useRoute, useRouter} from "vue-router";

const store = useStore();
const checker = computed(() => {
    return store.getters['Auth/getInit']
})
const router = useRouter();
const route = useRoute()

document.body.addEventListener('click',() => {
    if(route.fullPath.toString().indexOf('login') === -1) {
        if (checker.value) {
            router.push({name: 'Login', query: {error: btoa('Session is not valid')}})
        }
    }
})

</script>
<style lang="css">
@import "../css/app.css";
</style>
