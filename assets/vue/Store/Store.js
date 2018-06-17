import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

Vue.prototype.$http = axios

const url_allergies = "http://localhost:8000/api/allergies"
const url_categories = "http://localhost:8000/api/categories"
const url_products = "http://localhost:8000/api/products"
export const store = new Vuex.Store({
    state: {
        post_cart: 'http://localhost:8000/api/cart',
        site: {},
        allergies: [],
        categories: [],
        products: [],
        suggestions: [],
        cart: {
            'items': []
        }
    },
    mutations: {
        SET_SITE(state, infos){
            state.site = infos
        },
        SET_ALLERGIES(state, infos){
            state.allergies = infos
        },
        SET_CATEGORIES(state, infos){
            state.categories = infos
        },
        SET_PRODUCTS(state, infos){
            state.products = infos
        },
        SET_SUGGESTIONS(state, infos){
            state.suggestions = infos
        },/*
        ADD_PRODUCT_CART(state, p) {
            let flag = false
            state.cart.items.map(c => {
                if (c.product.slug ===p.slug){
                    c.qty ++
                    flag = true
                }
            })
            if (!flag){
                let qty = 1

            let line = {
                'qty': qty,
                'product': p
            }

            state.cart.items.push(line)
            }
        },*/
        UPDATE_PRODUCT_CART(state, datasObj) {
            /*console.log(datasObj)*/

                let found = state.cart.items.findIndex(line => {
                    return (line.item.slug === datasObj.item.slug)
                })

                if(found === -1){
                   state.cart.items.push(datasObj)
                }else {
                    Object.assign(state.cart.items[found], datasObj)
                }

        }
    },
    getters: {
        post_cart: state => state.post_cart,
        length_cart: state => {
            let total = state.cart.items.reduce(function(prev, current) {
                return prev + current.qty ;
            }, 0 )
            return total;
        },
        site : state => state.site,
        allergies : state => state.allergies,
        categories : state => state.categories,
        products : state => state.products,
        suggestions : state => state.suggestions,
        cart: state => state.cart
    },
    actions: {
        call_allergies ({commit}){
            axios.get(url_allergies).then((response) => {
                commit('SET_ALLERGIES', response.data)
            })
        },
        call_categories ({commit}){
            axios.get(url_categories).then((response) => {
                commit('SET_CATEGORIES', response.data)
            })
        },
        call_products ({commit}){
            axios.get(url_products).then((response) => {
                commit('SET_PRODUCTS', response.data)
            })
        },
        add_cart (context, payload){
            this.commit('ADD_PRODUCT_CART', payload.item)
        },
        update_cart (context, payload){

            this.commit('UPDATE_PRODUCT_CART', {'item': payload.item, 'qty': payload.qty})
        }

    }
})
