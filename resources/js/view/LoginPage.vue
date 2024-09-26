<template>
    <v-container class="fill-height" fluid>
        <DialogAlert id="ciao" :msg="dialog.msg" :dialog="dialog.show" :router-name="dialog.routerName">
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
import {computed, ref} from 'vue'
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import {api} from "../api/index.js";
import DialogAlert from "./common/DialogAlert.vue";

const email = ref('')
const password = ref('')
const valid = ref(false)
const form = ref(null)
const store = useStore();
const router = useRouter();
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
            let data = r.data.data
            store.commit('Auth/update',data.token)
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
                msg: e.response.data.errors !== undefined ? e.response.data.errors : e.message
            })
        })
     }
 })
}
</script>
