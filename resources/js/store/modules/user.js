
const state = () => ({
    data:{
        user:null
    },
})
const getters = {
    getUser:function(state){return state.data.user},
}

const actions = {}
const mutations = {
    update (state,payload){
        state.data.user = payload
    },

}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
