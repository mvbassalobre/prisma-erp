<template>
    <div @dblclick="editField" style="height: 22px; width: 100%">
        <template v-if="editing">
            <template v-if="type == 'select'">
                <select ref="input" class="w-100" :value="value" @keyup.enter="(e) => changeInput(e.target.value)" @blur="(e) => changeInput(e.target.value)">
                    <option v-for="(o, i) in options" :value="o.value" v-html="o.value ? o.label : o.value" :key="i" />
                </select>
            </template>
            <template v-if="['text', 'number', 'date'].includes(type)">
                <input
                    ref="input"
                    class="w-100"
                    :value="value"
                    :type="type"
                    :step="step"
                    @keyup.enter="(e) => changeInput(e.target.value)"
                    @blur="(e) => changeInput(e.target.value)"
                />
            </template>
        </template>
        <template v-else>
            <div style="cursor: pointer" v-html="val()"></div>
        </template>
    </div>
</template>
<script>
import moment, { months } from 'moment'
export default {
    props: {
        can_edit: {
            type: Boolean,
            default: true,
        },
        type: {
            type: String,
            default: 'text',
        },
        step: {
            type: Number,
            default: 0.01,
        },
        currency: {
            type: Boolean,
            default: false,
        },
        options: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            editing: false,
            value: this.$attrs.value ? this.$attrs.value : this.$props.value,
        }
    },
    watch: {
        '$attrs.value'(val) {
            this.value = val
        },
    },
    methods: {
        changeInput(value) {
            this.editing = false
            if (this.type == 'number') value = Number(value)
            this.value = value
            this.$emit('input', value)
            this.$emit('change', value)
        },
        editField(index, field) {
            if (!this.can_edit) return
            this.editing = true
            this.$nextTick(() => {
                this.$refs.input.focus()
            })
        },
        val() {
            if (this.type == 'date') {
                moment.locale('pt-br')
                return moment(this.value).format('L')
            }
            if (this.currency) return Number(this.value ? this.value : 0).currency()
            return this.value
        },
    },
}
</script>