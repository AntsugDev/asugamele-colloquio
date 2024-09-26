const state = () => ({
    data:{
        token:null,
    }
})
const getters = {
    getToken:function(state){return state.data.token},
}

const actions = {}
const mutations = {
    update (state,payload){
        state.data.token = payload
    },

}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
