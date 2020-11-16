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
	const year = state.years[state.years.findIndex(x => x.id == payload.year_id)]
	Vue.set(year, 'entries', payload.data)
}

export function setEntriesLoading(state, payload) {
	Vue.set(state.entries_loading, payload.year_id, payload.state)
}

export function setSections(state, payload) {
	const year = state.years[state.years.findIndex(x => x.id == payload.year_id)]
	Vue.set(year, 'sections', payload.data)
}

export function setSectionsLoading(state, payload) {
	Vue.set(state.sections_loading, payload.year_id, payload.state)
}

export function setExpenses(state, payload) {
	const year = state.years[state.years.findIndex(x => x.id == payload.year_id)]
	const section = year.sections[year.sections.findIndex(x => x.id == payload.section_id)]
	Vue.set(section, 'expenses', payload.expenses)
}

