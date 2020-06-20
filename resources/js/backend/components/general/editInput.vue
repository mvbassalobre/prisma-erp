<template>
    <div @dblclick="editField" style="height: 22px;width: 100%;">
        <template v-if="editing">
            <template v-if="type=='select'">
                <select
                    ref="input"
                    class="w-100"
                    :value="value"
                    @keyup.enter="(e) => changeInput(e.target.value)"
                    @blur="(e) => changeInput(e.target.value)"
                >
                    <option
                        v-for="(o,i) in options"
                        :value="o.value"
                        v-html="o.value ? o.label : o.value"
                        :key="i"
                    />
                </select>
            </template>
            <template v-if="['text','number'].includes(type)">
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
            <div v-html="val()"></div>
        </template>
    </div>
</template>
<script>
export default {
    props: {
        type: {
            type: String,
            default: "text"
        },
        step: {
            type: Number,
            default: 0.01
        },
        currency: {
            type: Boolean,
            default: false
        },
        options: {
            type: Array,
            default: () => ([])
        }
    },
    data() {
        return {
            editing: false,
            value: this.$attrs.value
        }
    },
    methods: {
        changeInput(value) {
            this.editing = false
            if (this.type == "number") value = Number(value)
            this.value = value
            this.$emit('input', value)
        },
        editField(index, field) {
            this.editing = true
            this.$nextTick(() => {
                this.$refs.input.focus()
            })
        },
        val() {
            if (this.currency) return Number(this.value).currency()
            return this.value
        }
    }
}
</script>