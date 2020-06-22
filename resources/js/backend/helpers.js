String.prototype.currency = function () {
    let decimal = Number(this).toFixed(2)
    return Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(decimal)
}
Number.prototype.currency = function () {
    return this.toString().currency()
}
Number.prototype.pad = function (size) {
    var s = String(this)
    while (s.length < (size || 2)) { s = "0" + s }
    return s
}

import Vue from 'vue'
Vue.prototype.$validatorError = function (data) {
    let message = "<ul>"
    for (let row in data) {
        message += `<li>${data[row][0]}</li>`
    }
    message += "</ul>"
    return message
}

Vue.prototype.$colors = {
    _RGBtoCMYK: function (rgb) {
        rgb = rgb.replace("rgb(", "").replace(")", "").split(",").map(x => Number(x) / 255)
        let cmyk = {}
        cmyk.k = 1 - Math.max(rgb[0], rgb[1], rgb[2])
        cmyk.c = (1 - rgb[0] - cmyk.k) / (1 - cmyk.k)
        cmyk.m = (1 - rgb[1] - cmyk.k) / (1 - cmyk.k)
        cmyk.y = (1 - rgb[2] - cmyk.k) / (1 - cmyk.k)
        Object.keys(cmyk).map(x => {
            cmyk[x] = (isNaN(cmyk[x]) ? 0 : cmyk[x]) * 100
        })
        return [cmyk.c, cmyk.m, cmyk.y, cmyk.k]
    },
    _CMYKtoRGB: function (cmyk) {
        let rgb = {}
        Object.keys(cmyk).map(x => {
            cmyk[x] = (isNaN(cmyk[x]) ? 0 : cmyk[x]) / 100
        })
        rgb.r = (1 - cmyk[0]) * (1 - cmyk[3])
        rgb.g = (1 - cmyk[1]) * (1 - cmyk[3])
        rgb.b = (1 - cmyk[2]) * (1 - cmyk[3])
        Object.keys(rgb).map(x => {
            rgb[x] = Math.round((isNaN(rgb[x]) ? 0 : rgb[x]) * 255)
        })
        return `rgb(${rgb.r},${rgb.g},${rgb.b})`
    }
}

Vue.prototype.$goTo = (url) => window.location.href = url


String.prototype.toClipboard = function (callback = () => { }) {
    let el = document.createElement('textarea')
    el.value = this
    el.setAttribute('readonly', '')
    el.style = { position: 'absolute', left: '-9999px' }
    document.body.appendChild(el)
    el.select()
    document.execCommand('copy')
    document.body.removeChild(el)
    callback()
}

Vue.prototype.$getMoths = () => [
    { name: "Janeiro", value: "jan", number: 1 },
    { name: "Fevereiro", value: "fev", number: 2 },
    { name: "Março", value: "mar", number: 3 },
    { name: "Abril", value: "abr", number: 4 },
    { name: "Maio", value: "mai", number: 5 },
    { name: "Junho", value: "jun", number: 6 },
    { name: "Julho", value: "jul", number: 7 },
    { name: "Agosto", value: "ago", number: 8 },
    { name: "Setembro", value: "set", number: 9 },
    { name: "Outubro", value: "out", number: 10 },
    { name: "Novembro", value: "nov", number: 11 },
    { name: "Dezembro", value: "dez", number: 12 },
]

String.prototype.currency = function () {
    let decimal = Number(this).toFixed(2)
    return Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(decimal)
}
Number.prototype.currency = function () {
    return this.toString().currency()
}
