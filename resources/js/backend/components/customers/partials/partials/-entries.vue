<template>
    <el-tabs type="border-card">
        <el-tab-pane v-for="(year,i) in years" :label="`${year}`" :key="i">
            <div class="row f-12">
                <div class="col-12">
                    <div class="card f-12">
                        <div class="card-header p-1">Entradas</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0 f-12 table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:350px" class="bordered">
                                                Conta
                                                <small>Mês</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th style="width:100px" :key="`${i}_head`">
                                                    {{ m.name.substring(0,3) }} /
                                                    <small>{{year}}</small>
                                                </th>
                                            </template>
                                        </tr>
                                        <tr>
                                            <th style="width:350px" class="bordered">
                                                Entradas
                                                <small>Receitas</small>
                                            </th>
                                            <template v-for="(m,i) in months">
                                                <th
                                                    style="width:100px"
                                                    class="f-10"
                                                    :key="`${i}_head_2`"
                                                >R$ 00,00</th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(q,y) in 30" :key="y">
                                            <td class="bordered">tetete</td>
                                            <template v-for="(m,i) in months">
                                                <td :key="`${i}_${y}_body`">tettet</td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </el-tab-pane>
    </el-tabs>
</template>
<script>
export default {
    props: ['months'],
    data() {
        return {
            entries: [],
            years: [2020]
        }
    },
    created() {
        this.entries = this.$attrs.value
    }
}
</script>