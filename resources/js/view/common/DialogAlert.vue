<template>
    <v-dialog v-model="dialogCust" min-width="200" max-width="400" >
        <v-card >
            <v-card-title class="headline"
                          :style="'background:'+colorHex+';color:#ffffff'">{{ title }}</v-card-title>
            <v-card-text>
                <slot name="msg" >{{msg}}</slot>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="accent"  type="button"
                       block variant="elevated"  @click="close">{{textBtn}}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup>
import {ref, defineProps, watch, onMounted, computed, onBeforeMount} from "vue";
import {useRoute, useRouter} from "vue-router";
import {store} from "../../store/index.js";
import {useTheme} from "vuetify";
const router = useRouter();
const theme = useTheme();

const colorHex = ref('success')

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
        nullable:true,
        default:null
    },
    title:{
        type:String,
        default:"Response Api",
        nullable:true
    },
    color:{
        type:String,
        default:'success',
        nullable:true
    }
})
const textBtn = ref('Close');

const dialogCust = ref(false)
const route = useRoute()

const close =() => {
    dialogCust.value = false
    if(props.routerName !== null) {
        if(props.routerName === "Reload"){
            router.push({name: route.name})
        }else
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
    if(value !== null) {
        if(value === "Reload" || value === undefined)
            textBtn.value = 'Close'
        else
            textBtn.value = "Go to " + props.routerName
    }
    else textBtn.value = 'Close'
})

onMounted(() => {
    dialogCust.value = props.dialog
    if(props.routerName !== null) {
        if (props.routerName === "Reload" || props.routerName === undefined)
            textBtn.value = 'Close'
        else
            textBtn.value = "Go to " + props.routerName
    } else textBtn.value = 'Close'
})

onBeforeMount(() => {
    if(theme.current.value.colors[props.color] !== undefined)
        colorHex.value = theme.current.value.colors[props.color];
    else
        colorHex.value = "#009688"
})

</script>
<style scoped lang="css">

</style>
