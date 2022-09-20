import "./validator"
import { noop } from 'element-ui/src/utils/util'
import AsyncValidator from 'async-validator'
import ElementUi from 'element-ui'
import lang from 'element-ui/lib/locale/lang/pt-br'
import VueCurrencyInput from 'vue-currency-input'
import Vue from 'vue'
import locale from 'element-ui/lib/locale'
Vue.use(VueCurrencyInput)
locale.use(lang)

ElementUi.FormItem.methods.validate = function (trigger,callback = noop) {
    this.validateDisabled = false
    const rules = this.getFilteredRule(trigger)
    if ((!rules || rules.length === 0) && this.required === undefined) {
        callback()
        return true
    }

    this.validateState = 'validating'

    const descriptor = {}
    if (rules && rules.length > 0) {
        rules.forEach(rule => {
            delete rule.trigger
        })
    }
    descriptor[this.prop] = rules

    const validator = new AsyncValidator(descriptor)
    const model = {}

    model[this.prop] = this.fieldValue
    let fieldName = this.label || this.$attrs.propname

    validator.validate(model,{ firstFields: true },(errors,invalidFields) => {
        this.validateState = !errors ? 'success' : 'error'
        this.validateMessage = errors ? errors[0].message.replace(this.prop,fieldName).replace(rules[0].type,'') : ''

        callback(this.validateMessage,invalidFields)
        this.elForm && this.elForm.$emit('validate',this.prop,!errors,this.validateMessage || null)
    })
}

Vue.use(ElementUi)