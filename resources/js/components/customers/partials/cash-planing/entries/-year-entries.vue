<template>
    <tbody v-loading="loading">
        <tr v-for="(entry, y) in year.entries" :key="y">
            <td class="w-25">
                <edit-input v-model="entry.name" :can_edit="true" @change="changeValue(entry)" />
            </td>
            <template v-for="(m, i) in months">
                <td :key="`${i}_${y}_body`">
                    <edit-input type="number" v-model="entry[m.value]" :currency="true" :can_edit="true" @change="changeValue(entry, m)" />
                </td>
            </template>
            <td class="text-center">
                <button class="append-btn" type="button" @click.prevent="deleteEntry(entry)">
                    <span class="el-icon-error text-danger"></span>
                </button>
            </td>
        </tr>
        <tr>
            <td>
                <input class="w-100 mr-1" v-model="form.name" />
            </td>
            <template v-for="(m, i) in months">
                <td :key="`${i}_form`">
                    <input class="w-100 mr-1" v-model.number="form[m.value]" type="number" step="0.01" @change="setAllValues(m)" />
                </td>
            </template>
            <td class="text-center">
                <button class="append-btn" type="button" @click.prevent="addEntry">
                    <span class="el-icon-success text-success"></span>
                </button>
            </td>
        </tr>
    </tbody>
</template>
<script>
export default {
    props: ['year'],
    data() {
        return {
            form: {
                name: null,
            },
        }
    },
    created() {
        this.init()
    },
    computed: {
        cash_planing() {
            return this.$store.state.cash_planing
        },
        loading() {
            return this.cash_planing.entries_loading[this.year.id] ? this.cash_planing.entries_loading[this.year.id] : false
        },
        months() {
            return this.$store.getters['global/getMonths']
        },
    },
    methods: {
        init() {
            this.months.map(({ value }) => this.$set(this.form, value, 0))
        },
        refreshForm() {
            this.form.name = null
            let months_values = this.months.map(({ value }) => value)
            Object.keys(this.form).map((k) => {
                if (months_values.includes(k)) return this.$set(this.form, k, 0)
                return this.$set(this.form, k, null)
            })
        },
        setAllValues(current_month) {
            this.months.forEach((month) => {
                if (month.number > current_month.number) this.form[month.value] = this.form[current_month.value]
            })
        },
        addEntry() {
            if (!this.form.name) return this.$message.warning('De um nome a esta entrada !!')
            this.$store.dispatch('cash_planing/addEntry', {
                form: this.form,
                year_id: this.year.id,
                callback: () => this.refreshForm(),
            })
        },
        changeValue(entry, current_month = null) {
            if (current_month) {
                let months = this.months.filter((m) => m.number > current_month.number)
                months.forEach((month) => {
                    let value = entry[current_month.value]
                    let index = month.value
                    entry[index] = value
                })
            }
            this.$store.dispatch('cash_planing/changeEntry', entry)
        },
        deleteEntry(entry) {
            this.$confirm('Deseja excluir ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.$store.dispatch('cash_planing/deleteEntry', { entry_id: entry.id, year_id: this.year.id })
            })
        },
    },
}
</script>