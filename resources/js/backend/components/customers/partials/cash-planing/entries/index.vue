<template>
    <div class="row f-12">
        <div class="col-12">
            <div class="card f-12">
                <div class="card-header p-1"><span class="el-icon-circle-plus mr-2"></span>Entradas</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0 f-12 table-hover">
                            <thead>
                                <tr>
                                    <th class="w-25">Conta<small>Mês</small></th>
                                    <template v-for="(m, i) in months">
                                        <th style="width: 100px" :key="`${i}_head`" class="green">
                                            {{ m.value }} /
                                            <small>{{ year.value }}</small>
                                        </th>
                                    </template>
                                    <th class="green" />
                                </tr>
                                <tr class="w-25">
                                    <th>Entradas<small>Receitas</small></th>
                                    <template v-for="(m, i) in months">
                                        <th style="width: 150px" class="f-10 green2" :key="`${i}_head_2`">
                                            {{ total(year.entries, m).currency() }}
                                        </th>
                                    </template>
                                    <th class="green2" />
                                </tr>
                            </thead>
                            <year-entries :year="year" />
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import yearEntries from './-year-entries'
export default {
    props: ['year'],
    components: {
        yearEntries,
    },
    computed: {
        months() {
            return this.$store.getters['global/getMonths']
        },
    },
    methods: {
        total(entries, month) {
            entries = entries ? entries : []
            return entries.map((e) => Number(e[month.value])).reduce((a, b) => a + b, 0)
        },
    },
}
</script>