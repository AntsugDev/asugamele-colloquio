<template>
    <v-container class="fill-height" fluid>
        <DialogAlert id="ciao" :msg="dialog.msg"
                     :dialog="dialog.show"
                     :router-name="dialog.routerName">
        </DialogAlert>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="6" lg="4">
                <v-card elevation="4">
                    <v-card-title class="text-center text-h5 py-4">
                        Login
                    </v-card-title>
                    <v-card-text>
                        <v-form  v-model="valid" ref="form">
                            <v-text-field
                                v-model="email"
                                label="Email"
                                prepend-icon="mdi-email"
                                type="email"
                                required
                                :rules="[v => !!v || 'Required Field']"
                            ></v-text-field>
                            <v-text-field
                                v-model="password"
                                label="Password"
                                prepend-icon="mdi-lock"
                                type="password"
                                required
                                :rules="[v => !!v || 'Required Field']"
                            ></v-text-field>

                        </v-form>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-4">
                        <v-btn
                            type="button"
                            color="primary"
                            block
                            class="mt-4"
                            @click="login"
                            variant="elevated"
                            :loading="load"
                        >
                            Login
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import {useStore} from "vuex";
import {useRoute, useRouter} from "vue-router";
import {api} from "../api/index.js";
import DialogAlert from "./common/DialogAlert.vue";
import dayjs from "dayjs";

const email = ref('')
const password = ref('')
const valid = ref(false)
const form = ref(null)
const store = useStore();
const router = useRouter();
const route = useRoute();
const dialog =computed(() => {
    return store.getters['Dialog/getDialog']
})
const load = ref(false);
const login = () => {
    load.value = true;
 form.value.validate().then(r => {
     if(r.valid){
        api('/auth','POST',{
            email: email.value,
            password: password.value
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
                msg: "User authorized",
                routerName: 'Table'
            })
            load.value = false;

        }).catch(e => {
            load.value = false;
            store.commit('Dialog/update',{
                show:true,
                msg:  e.message
            })
        })
     }
 })
}
const logout = ()  =>{
    api('/logout','GET')
}


watch(() => route.query.error, (value) => {
    if(value !== undefined)
        store.commit('Dialog/update',{
            show:true,
            msg: route.query.error !== null ? atob(route.query.error) : null,
            routerName: 'Reload'
        })
})

onMounted(() => {
    if(route.query.error !== undefined)
        store.commit('Dialog/update',{
            show:true,
            msg: route.query.error !== null ? atob(route.query.error) : null,
            routerName: 'Reload'
        })

    if(route.query.logout !== undefined && route.query.logout) {
        logout();
        store.commit('Dialog/update', {
            show: true,
            msg: "Logout success!",
            routerName: 'Reload'
        })
    }
})

</script>
