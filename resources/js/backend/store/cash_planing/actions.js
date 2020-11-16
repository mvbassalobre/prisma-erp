import api from 'axios'
var attempts = 0
const route = '/admin/customers/cash-planing'

export function getYears({ state, dispatch, commit }) {
	attempts++
	commit('setLoading', true)
	api.post(`${route}/get-years`, { customer_id: state.customer.id }).then(({ data }) => {
		commit('setLoading', false)
		commit("setYears", data)
		state.years.map(y => dispatch('start', y.id))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("getYears")
		commit("setLoading", false)
		attempts = 0
		return console.log(er)
	})
}

export function start({ dispatch }, year_id) {
	dispatch('getEntries', year_id)
	dispatch('getSections', year_id)
}

export function getSections({ dispatch, commit }, payload) {
	attempts++
	api.post(`${route}/get-sections`, { year_id: payload }).then(({ data }) => {
		commit('setSections', { year_id: payload, data })
		data.map(section => dispatch("getExpenses", { section_id: section.id, year_id: payload }))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("getSections", payload)
		attempts = 0
		return console.log(er)
	})
}

export function getExpenses({ dispatch, commit }, payload) {
	attempts++
	api.post(`${route}/get-expenses`, payload).then(({ data }) => {
		commit('setExpenses', { year_id: payload.year_id, section_id: payload.section_id, expenses: data })
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("getExpenses", payload)
		attempts = 0
		return console.log(er)
	})
}

export function getEntries({ dispatch, commit }, payload) {
	attempts++
	commit('setEntriesLoading', { state: true, year_id: payload })
	api.post(`${route}/get-entries`, { year_id: payload }).then(({ data }) => {
		commit('setEntries', { year_id: payload, data })
		commit('setEntriesLoading', { state: false, year_id: payload })
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("getYears", payload)
		commit('setEntriesLoading', { state: false, year_id: payload })
		attempts = 0
		return console.log(er)
	})
}

export function addEntry({ state, dispatch, commit }, payload) {
	attempts++
	commit('setEntriesLoading', { state: true, year_id: payload.year_id })
	api.post(`${route}/add-entries`, { ...payload, customer_id: state.customer.id }).then(({ data }) => {
		dispatch("getYears")
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
		if (payload.callback) payload.callback()
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("addEntry", payload)
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
		attempts = 0
		return console.log(er)
	})
}

export function changeEntry({ state, dispatch, commit }, payload) {
	attempts++
	commit('setEntriesLoading', { state: true, year_id: payload.year_id })
	api.put(`${route}/edit-entry/${payload.id}`, payload).then(({ data }) => {
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(year_id => dispatch('start', year_id))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 0) return dispatch('changeEntry', payload)
		attempts = 0
		console.log(er)
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
	})
}

export function deleteEntry({ state, dispatch, commit }, payload) {
	attempts++
	commit('setEntriesLoading', { state: true, year_id: payload.year_id })
	api.delete(`${route}/delete-entry/${payload.entry_id}`).then(({ data }) => {
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(year_id => dispatch('start', year_id))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 0) return dispatch('deleteEntry', payload)
		attempts = 0
		console.log(er)
		commit('setEntriesLoading', { state: false, year_id: payload.year_id })
	})
}

export function addYears({ state, dispatch, commit }, payload) {
	let new_years = [payload.new_year]
	for (let i = 1;i <= payload.more;i++) new_years.push(payload.new_year + i)
	commit("setLoading", true)
	attempts++
	api.post(`${route}/add-year`, { new_years, customer_id: state.customer.id }).then(({ data }) => {
		commit("setYears", data.years)
		commit("setLoading", false)
		dispatch('getYears')
		attempts = 0
	}).catch((er) => {
		if (attempts <= 0) return dispatch('addYears', payload)
		attempts = 0
		console.log(er)
		commit("setLoading", false)
	})
}


export function removeYear({ state, dispatch, commit }, payload) {
	commit("setLoading", true)
	api.delete(`${route}/delete-year/${payload.id}`).then(({ data }) => {
		commit("setLoading", false)
		dispatch("getYears")
		attempts = 0
	}).catch((er) => {
		if (attempts <= 0) return dispatch('removeYear', payload)
		attempts = 0
		console.log(er)
		commit("setLoading", false)
	})
}


export function addSections({ state, dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.post(`${route}/add-section`, { ...payload, customer_id: state.customer.id }).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("addSections", payload)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		attempts = 0
		return console.log(er)
	})
}


export function deleteSection({ dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.delete(`${route}/delete-section/${payload.section_id}`).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("deleteSection", payload)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		attempts = 0
		return console.log(er)
	})
}


export function editSection({ dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.put(`${route}/edit-section/${payload.section.id}`, payload.section).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("editSection", payload)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		return console.log(er)
	})
}


export function addExpense({ dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.post(`${route}/add-expense`, payload).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		if (payload.callback) payload.callback()
		attempts = 0
	}).catch((er) => {
		if (attempts <= 0) return dispatch('addExpense', payload)
		attempts = 0
		console.log(er)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
	})
}

export function deleteExpense({ dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.delete(`${route}/delete-expense/${payload.expense_id}`).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("deleteSection", payload)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		attempts = 0
		return console.log(er)
	})
}

export function editExpense({ dispatch, commit }, payload) {
	attempts++
	commit('setSectionsLoading', { state: true, year_id: payload.year_id })
	api.put(`${route}/edit-expense/${payload.expense.id}`, payload.expense).then(({ data }) => {
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		data.year_ids.map(y => dispatch('getSections', y))
		attempts = 0
	}).catch((er) => {
		if (attempts <= 3) return dispatch("editSection", payload)
		commit('setSectionsLoading', { state: false, year_id: payload.year_id })
		attempts = 0
		return console.log(er)
	})
}
