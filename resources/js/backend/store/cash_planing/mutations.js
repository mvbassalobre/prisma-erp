import Vue from 'vue'

export function setCustomer(state, payload) {
	state.customer = payload
}

export function setYears(state, payload) {
	state.years = payload
}

export function setLoading(state, payload) {
	state.loading = payload
}

export function setEntries(state, payload) {
	Vue.set(state.years[state.years.findIndex(x => x.id == payload.year_id)], 'entries', payload.data)
}

export function setEntriesLoading(state, payload) {
	Vue.set(state.entries_loading, payload.year_id, payload.state)
}

export function setSections(state, payload) {
	Vue.set(state.years[state.years.findIndex(x => x.id == payload.year_id)], 'sections', payload.data)
}
