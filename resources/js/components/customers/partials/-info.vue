<template>
    <div class="tab-pane fade" v-bind:class="{ 'show active': active == 'info' }" id="v-pills-info" role="tabpanel"
        aria-labelledby="v-pills-info-tab">
        <div class="row f-12">
            <div class="col-12" v-loading="loading" element-loading-text="Carregando...">

                <div class=" row mb-3" v-for="(card,i) in info.fields" :key="i" v-if="!loading">
                    <div class="col-12">
                        <div class="card mb-3" :id="`${card.label}`">
                            <div class="card-header crud-card-header">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <div><b v-html="card.label" class="card-title" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped mb-0 read-only">
                                            <tbody>
                                                <template v-for="(input,y) in card.inputs">
                                                    <v-runtime-template :template="input.view" />
                                                </template>
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
            errors: {},
            loading: true
        }
    },
    components: {
        'v-runtime-template': VRuntimeTemplate,
    },
    created() {
        setTimeout(() => {
            if (!this._isDestroyed) {
                for (let i in this.info.fields) {
                    let card = this.info.fields[i];
                    for (let y in card.inputs) {
                        let input = card.inputs[y];
                        this.$set(this.form, input.options?.field, input.options?.value);
                    }
                }
                this.loading = false;
            }
        });
    },
}
</script>
<style lang="scss">
.read-only {

    input {
        border: none;
        pointer-events: none;
        background-color: transparent;
    }

}
</style>