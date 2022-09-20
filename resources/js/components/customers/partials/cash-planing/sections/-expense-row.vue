<template>
    <tr>
        <td>
            <edit-input @change="changeValue" v-model="expense.name" />
        </td>
        <template v-for="(m, i) in months">
            <td :key="`${i}_body`">
                <edit-input type="number" v-model="expense[m.value]" :currency="true" @change="changeValue(m)" />
            </td>
        </template>
        <td class="text-center">
            <button class="append-btn" type="button" @click="deleteExpense">
                <span class="el-icon-error text-danger"></span>
            </button>
        </td>
    </tr>
</template>
<script>
export default {
    props: ['expense', 'months', 'year'],
    methods: {
        changeValue(current_month = null) {
            if (current_month) {
                let months = this.months.filter((m) => m.number > current_month.number)
                months.forEach((month) => {
                    let value = this.expense[current_month.value]
                    let index = month.value
                    this.$set(this.expense, index, value)
                })
            }
            this.$store.dispatch('cash_planing/editExpense', {
                year_id: this.year.id,
                expense: this.expense,
            })
        },
        deleteExpense(expense) {
            this.$confirm('Deseja excluir ?', 'Confirmação', {
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                type: 'warning',
            }).then(() => {
                this.$store.dispatch('cash_planing/deleteExpense', {
                    year_id: this.year.id,
                    expense_id: this.expense.id,
                })
            })
        },
    },
}
</script>