<template>
    <div class="row d-flex flex-row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <b class="f-12">
                        <span class="el-icon-s-custom mr-2" />Selecione o cliente que deseja efetuar o lançamento
                    </b>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="w-25 text-center pt-2">Cliente</td>
                                        <td class="w-75">
                                            <el-select
                                                class="w-100"
                                                placeholder="Selecione o cliente"
                                                v-model="customer_code"
                                            ></el-select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            customer_code: ""
        }
    }
}
</script>