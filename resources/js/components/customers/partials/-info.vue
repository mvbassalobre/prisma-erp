<template>
    <div class="tab-pane fade" v-bind:class="{ 'show active': active == 'info' }" id="v-pills-info" role="tabpanel"
        aria-labelledby="v-pills-info-tab">
        <div class="row f-12">
            <div class="col-12">

                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3" :id="`Endereço`">
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 25%"><b>Cep</b></td>
                                                    <td>{{ customer.zipcode }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Estado</b></td>
                                                    <td>{{ customer.state }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Cidade</b></td>
                                                    <td>{{ customer.city }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Bairro</b></td>
                                                    <td>{{ customer.district }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Rua</b></td>
                                                    <td>{{ customer.street }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Número</b></td>
                                                    <td>{{ customer.number }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%"><b>Complemento</b></td>
                                                    <td>{{ customer.complement }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'
export default {
    props: ['info', 'active', 'customer'],
    data() {
        return {
            form: {},
            errors: {}
        }
    },
    components: {
        'v-runtime-template': VRuntimeTemplate,
    },
}
</script>