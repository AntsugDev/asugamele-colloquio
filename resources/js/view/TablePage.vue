<template>
    <v-container class="fill-height" fluid>
        <v-row align="start" justify="start">
            <v-col cols="12" >
                <v-card elevation="4">
                    <v-card-title class="text-center text-h5 py-4">
                        Table
                    </v-card-title>
                    <v-card-text>
                        <div class="table">
                            <div class="row">
                                <div class="cellHeader" v-for="h in headers" :key="h.key">{{h.title}}</div>
                            </div>
                            <div class="row" v-if="loading">
                                <v-progress-linear indeterminate color="accent"></v-progress-linear>
                            </div>
                            <template v-else-if="!loading && items.length > 0">
                                <div  :class="'row '+(k%2 === 0 ? 'even' : 'odd')" v-for="(i,k) in items" :key="i" >
                                    <div class="cellBody">{{i.name}}</div>
                                    <div class="cellBody">{{i.brewery_type}}</div>
                                    <div class="cellBody">{{i.city}}</div>
                                    <div class="cellBody">{{i.state_province}}</div>
                                    <div class="cellBody">{{i.country}}</div>
                                    <div class="cellBody">{{i.phone}}</div>
                                    <div class="cellBody">{{i.website_url}}</div>
                                    <div class="cellBody">{{i.state}}</div>
                                </div>
                                <div style="height: 2vw"></div>
                                <div class="footerTable">
                                    <v-select style="max-width:150px!important"
                                              :items="[5,10,25,50]"
                                              v-model="itemsPerPage"
                                              @update:model-value="tableList"
                                    ></v-select>
                                    <div style="width:150px;margin-top:10px;margin-left: 20px " v-if="itemsPerPage && page">
                                        <v-icon icon="mdi-chevron-left" size="40" v-if="page > 1" @click="changePage(false)"></v-icon>
                                        <v-badge color="accent" text-color="white" :content="viewPage"></v-badge>
                                        <v-icon icon="mdi-chevron-right" size="40" v-if="page < totPage" @click="changePage(true)" ></v-icon>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="!loading && items.length === 0">
                                <div class="row" >
                                    Nessun elemento trovato
                                </div>
                            </template>
                        </div>


                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import {computed, onBeforeMount, ref} from "vue";
import {api} from "../api/index.js";


const items = ref([])
const loading = ref(true)
const itemsPerPage = ref(5)
const page = ref(1)
const totPage = ref(0)
const viewPage = ref('')

const headers = computed(() =>{
    return [
        {
            title: 'Name',
            align: 'center',
            sortable: true,
            key: 'name',
        },
        {
            title: 'Brewery Type',
            align: 'center',
            sortable: true,
            key: 'brewery_type',
        },
        {
            title: 'City',
            align: 'center',
            sortable: true,
            key: 'city',
        },
        {
            title: 'State province',
            align: 'center',
            sortable: true,
            key: 'state_province',
        },
        {
            title: 'Country',
            align: 'center',
            sortable: true,
            key: 'country',
        },
        {
            title: 'Phone',
            align: 'center',
            sortable: true,
            key: 'phone',
        },
        {
            title: 'Website',
            align: 'center',
            sortable: true,
            key: 'website_url',
        },
        {
            title: 'State',
            align: 'center',
            sortable: true,
            key: 'state',
        },

    ]
})
const changePage = (isState) => {
    page.value = isState ? (parseInt(page.value)+1) : (parseInt(page.value)-1)
    tableList()
}

const tableList = () => {
    loading.value = true
    api('list', 'GET', null, "page="+page.value+"&size="+itemsPerPage.value).then(r => {
        let data = r.data.data;
        items.value = data.content
        totPage.value = data.totalPage
        itemsPerPage.value = data.size
        page.value = data.page
        viewPage.value =totPage.value.toString()!== '0' ?  page.value.toString()+'/'+totPage.value.toString() : page.value.toString()
        loading.value = false
    }).catch(e => {
        loading.value = false
    })
}
onBeforeMount(() =>{
    tableList()
})

</script>
<style scoped>
.table{
    width: 100%!important;
    display: flex;
    flex-direction: column;
}
.row{
    width: 100%!important;
    display: flex;
    flex-direction: row;
    border-bottom: 2px solid #f0f0f0;
}
.cellHeader{
    margin-bottom: 5px;
    padding: 1vw;
    font-weight: 700;
    min-width: 13%;
    justify-content: start;
    text-align: start;
}

.cellBody{
    margin-bottom: 5px;
    padding: 1vw;
    min-width: 13%;
    justify-content: start;
    text-align: start;
}
.even{
    background: #f2f3f4;
    border-bottom-color:#f2f3f4 ;
}
.odd{
    background: #eaeded;
    border-bottom-color:#eaeded ;
}
.footerTable{
    width: 100%;
    justify-content: end;
    margin-right: 10px;
    display: flex;
    flex-direction: row;
}
</style>
<style>
.v-badge{
    margin-left: 30px;
    margin-right: 30px;
    margin-bottom: 3px;
}
.v-badge__badge{
    height: 50px;
    width: 50px;
}
</style>
