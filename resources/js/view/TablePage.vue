<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="1" ></v-col>
            <v-col cols="10" >
                <v-card elevation="4">
                    <v-card-title class="text-center text-h5 py-4">
                        Table
                    </v-card-title>
                    <v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="table"
                            :loading="load"
                        >

                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import {computed, onBeforeMount, ref} from "vue";
import {api} from "../api/index.js";


const table = ref([])
const load= ref(false);
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
            title: 'Address one',
            align: 'center',
            sortable: true,
            key: 'address_1',
        },
        {
            title: 'Address two',
            align: 'center',
            sortable: true,
            key: 'address_2',
        },
        {
            title: 'Address tree',
            align: 'center',
            sortable: true,
            key: 'address_3',
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
            title: 'Postal code',
            align: 'center',
            sortable: true,
            key: 'postal_code',
        },
        {
            title: 'Country',
            align: 'center',
            sortable: true,
            key: 'country',
        },
        {
            title: 'Longitude',
            align: 'center',
            sortable: true,
            key: 'longitude',
        },
        {
            title: 'Latitude',
            align: 'center',
            sortable: true,
            key: 'latitude',
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
        {
            title: 'Street',
            align: 'center',
            sortable: true,
            key: 'street',
        },
    ]
})

const tableList = () => {
    load.value = true
    api('list', 'GET').then(r => {
        console.log('result',r.data)
        table.value = r.data
        load.value = false

    }).catch(e => {
        console.log('e',e)
        load.value = false
    })
}
onBeforeMount(() =>{
    tableList();
})

</script>
<style scoped>

</style>
