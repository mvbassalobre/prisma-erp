<template>
    <div class="tab-pane fade" v-bind:class="{ 'show active': active == 'info' }" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
        <div class="row f-12">
            <div class="col-12">
                <div class="row" v-for="(card, i) in info.fields.filter(({ label }) => !['Informações', 'Endereço'].includes(label))" :key="i">
                    <div class="col-12">
                        <div class="card mb-3" :id="`${card.label}`">
                            <div v-if="card.label" class="card-header">
                                <h5 v-html="card.label"></h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr v-for="(field, i) in card.inputs" :key="i">
                                                    <td style="width: 25%" v-if="i.indexOf('IGNORE__') < 0">
                                                        <span v-html="`<b>${i}</b>`"></span>
                                                    </td>
                                                    <td>
                                                        <v-runtime-template :key="i" :template="`<span>${field === null ? '' : field}</span>`" />
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
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3" :id="`Endereço`">
                            <div class="card-header">
                                <h5 v-html="`Endereço`"></h5>
                            </div>
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
    components: {
        'v-runtime-template': VRuntimeTemplate,
    },
}
</script>