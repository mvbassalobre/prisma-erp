<template>
    <div class="row mb-3" v-loading="loading">
        <div class="col-12">
            <div class="row-responsive-table">
                <table class="table table-striped">
                    <tbody>
                        <tr class="mb-3">
                            <td>Cep</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            placeholder="Digite o cep aqui ..."
                                            name="email"
                                            v-model="form.zipcode"
                                            class="form-control"
                                            @blur="search_zipcode"
                                            v-mask="['##.###-###']"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Cidade</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input type="text" placeholder="Digite a cidade aqui ..." name="city" v-model="form.city" class="form-control" />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Estado</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input type="text" placeholder="Digite o estado aqui ..." name="state" v-model="form.state" class="form-control" />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Bairro</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            placeholder="Digite o bairro aqui ..."
                                            name="district"
                                            v-model="form.district"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Rua</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input type="text" placeholder="Digite a rua aqui ..." name="street" v-model="form.street" class="form-control" />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Número</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input type="text" placeholder="Digite o numero aqui ..." name="number" v-model="form.number" class="form-control" />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr class="mb-3">
                            <td>Complemento</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            placeholder="Digite o complemento aqui ..."
                                            name="complement"
                                            v-model="form.complement"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['form'],
    data() {
        return {
            loading: false,
            attempts: 0,
        }
    },
    methods: {
        search_zipcode() {
            this.loading = true
            this.attempts++
            const zipcode = (this.form.zipcode || '').replace(/\D/, '').replace(/[^\d]/, '')
            console.log(zipcode)

            return this.$http
                .get(`https://ws.apicep.com/cep/${zipcode}.json`, {})
                .then(({ data }) => {
                    this.form.city = data.city
                    this.form.state = data.state
                    this.form.district = data.district
                    this.form.street = data.address
                    this.form.complement = data.complemento
                    this.attempts = 0
                    this.loading = false
                })
                .catch((er) => {
                    if (this.attempts <= 3) return this.search_zipcode()
                    console.log(er)
                    this.loading = false
                    this.attempts = 0
                })
        },
    },
}
</script>