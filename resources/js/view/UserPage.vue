<template>
    <v-container>
        <v-card class="mx-auto" max-width="400">
            <v-card-title>{{ user.name }}</v-card-title>
            <v-card-text>
                <div style="display: flex;flex-direction: row">
                    <v-icon>mdi-email</v-icon>&nbsp;{{ user.email }}
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn color="warning" variant="elevated"  @click="refresh" :loading="load">
                    Refresh
                </v-btn>
                <v-btn color="success" variant="elevated" @click="logout" :loading="load">
                    Logout
                </v-btn>
                <v-btn color="success" variant="elevated" @click="view" :loading="load">
                    View Token
                </v-btn>

            </v-card-actions>
        </v-card>
        <DialogAlert  :dialog="dialog.dialog" title="View token api" router-name="Reload">
            <template v-slot:msg>
                <pre >{{dialog.msg}}</pre>
            </template>
        </DialogAlert>
    </v-container>
</template>
<script setup>
import {computed, ref} from "vue";
import {useStore} from "vuex";
import {api} from "../api/index.js";
import dayjs from "dayjs";
import DialogAlert from "./common/DialogAlert.vue";
import {useRouter} from "vue-router";
const store = useStore();
const user = computed(() => {
    return store.getters['User/getUser']
})
const load = ref(false)

const refreshToken = computed(() =>  {
    return store.getters['Auth/getRefreshToken']
})

const dialog = ref({
    dialog: false,
    msg: null,

})
const router = useRouter();

const view = () => {
    dialog.value.msg =  store.getters['Auth/getToken'];
    dialog.value.dialog = true;
}

const refresh = () => {
    load.value = true;
    if(refreshToken.value !== null){
        api('/refresh','POST',{
            refresh_token: refreshToken.value
        }).then(r => {
            let data = r.data
            let user = data.data;
            store.commit('User/update',user)
            let token = data["data-token"];
            store.commit('Auth/update',{
                token:token.access_token,
                refresh_token:token.refresh_token,
                expired:token.expires_at,
                login: dayjs()
            })
            store.commit('Dialog/update',{
                show:true,
                msg: "Refresh token success",
                routerName: 'Reload'
            })
            load.value = false;

        }).catch(e => {
            store.commit('Dialog/update',{
                show:true,
                msg:  e.message
            })
            load.value = false;

        })
    }else
        load.value = false;
}

const logout = () => {
    router.push({name: 'Login',query:{logout: true}})
}
</script>
<style scoped lang="css">
pre{
    width: 100%;
    white-space: pre-wrap;
    border: 2px solid;
    padding: 2vw;
    border-radius: 10px;
}
</style>
