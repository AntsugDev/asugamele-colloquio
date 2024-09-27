<template>
    <v-container class="fill-height">
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card class="text-center pa-6" elevation="8">
                    <v-card-title class="text-h2 font-weight-bold error--text mb-4">
                        404
                    </v-card-title>
                    <v-card-text>
                        <p class="text-h5 mb-4">{{ data.errorMessage }}</p>
                        <p class="mb-6">{{ data.suggestion }}</p>
                        <v-btn color="primary" @click="goHome">
                            Go Login
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import {useRoute, useRouter} from "vue-router";
import {onBeforeMount, ref} from "vue";

const route = useRoute();
const router = useRouter()
const data = ref(null)
const goHome = () => {
    router.push({name:'Login'})
}

onBeforeMount(() => {
    if(route.query.data !== undefined){
        data.value = JSON.parse(atob(route.query))
    }
})
</script>
<style scoped lang="css">

</style>
