<template>
    <v-layout>
        <v-main class="d-flex justify-center">
            <DialogAlert
                         :msg="dialog.msg"
                         :dialog="dialog.show"
                         :router-name="dialog.routerName"
                         :color="dialog.color"
            >
            </DialogAlert>
            <router-view></router-view>
        </v-main>
    </v-layout>
</template>
<script setup>
import {useStore} from "vuex";
import {computed} from "vue";
import {useRoute, useRouter} from "vue-router";
import DialogAlert from "./view/common/DialogAlert.vue";

const store = useStore();
const checker = computed(() => {
    return store.getters['Auth/getInit']
})
const router = useRouter();
const route = useRoute()

const dialog = computed(() => {
    return store.getters['Dialog/getDialog']
})

document.body.addEventListener('click',() => {
    if(route.fullPath.toString().indexOf('login') === -1) {
        if (checker.value) {
            router.push({name: 'Login', query: {error: btoa('Session is not valid')}})
        }
    }
})

axios.interceptors.response.use((response) => {
    return response;
},(error) => {
    let data = error.response.data
    let message = "";
    if(data.data !== undefined && data.data.errors !== undefined)
        message = data.data.errors
    else
        message = e.message;
    store.commit('Dialog/update',{
        show:true,
        color:"error",
        msg: message,
        routeName: "Reload",
        title: "Execption response api"
    })

})

</script>
<style lang="css">
@import "../css/app.css";
</style>
