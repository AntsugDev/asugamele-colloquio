const state = () => ({
    data:{
        show:false,
        msg:null,
        routerName:null,
        color:null
    }
})
const getters = {
    getDialog:function(state){return state.data},
}

const actions = {}
const mutations = {
    update (state,payload){
        state.data = payload
    },

}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
