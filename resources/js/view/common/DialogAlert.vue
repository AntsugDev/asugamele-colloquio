<template>
    <v-dialog v-model="dialogCust" max-width="400">
        <v-card>
            <v-card-title class="headline">Response api</v-card-title>
            <v-card-text>{{msg}}</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary"  type="button"
                       block variant="elevated"  @click="close">{{textBtn}}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup>
import {ref, defineProps, watch, onMounted} from "vue";
import {useRouter} from "vue-router";
import {store} from "../../store/index.js";
const router = useRouter();
const props = defineProps({
    dialog:{
      type:Boolean,
      required:true,
      default: false
    },
    msg:{
        type: String,
        required:true
    },
    routerName:{
        type:String,
        nullable:true
    }
})
const textBtn = ref('Close');

const dialogCust = ref(false)

const close =() => {
    dialogCust.value = false
    if(props.routerName !== null) {
        router.push({name: props.routerName})
        store.commit('Dialog/update',{
            show:false,
            msg:null,
            routerName: null,
        })
    }else
        store.commit('Dialog/update',{
            show:false,
            msg:null,
            routerName: null,
        })
}
watch(() => props.dialog, (value) => {
    dialogCust.value = value
})
watch(()=> props.routerName, (value) => {
    if(value !== null)
        textBtn.value = "Go to "+props.routerName
})

onMounted(() => {
    dialogCust.value = props.dialog
    textBtn.value = props.routerName !== null ? "Go to "+props.routerName : 'Close'
})
</script>
<style scoped lang="css">

</style>
