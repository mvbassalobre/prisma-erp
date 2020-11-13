import Vue from 'vue'
import Vuex from 'vuex'

import cash_planing from './cash_planing'
import global from './global'

Vue.use(Vuex)

export default function () {
	const Store = new Vuex.Store({
		modules: {
			cash_planing,
			global
		},
	})

	return Store
}
