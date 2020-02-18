<template>
    <div class="mb-4">
        <v-tags
            :unique="true"
            label="White List"
            v-model="tags"
            description="White List é a lista de rotas que possuem permissão para acessar a api"
            :extraValidator="{'handle':isUrl,'message':'O valor deve ser uma URL'}"
        />
    </div>
</template>
<script>
export default {
    data() {
        return {
            tags: [],
            initialized: false,
            timeout: null
        }
    },
    watch: {
        tags(val) {
            if (!this.initialized) return
            clearTimeout(this.timeout)
            this.timeout = setTimeout(() => {
                this.update()
            }, 1000)
        }
    },
    async created() {
        this.initValues().then(x => this.initialized = true)
    },
    methods: {
        isUrl(url) {
            if (!url) return false
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))|' + // OR ip (v4) address
                'localhost' + // OR localhost
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i') // fragment locator
            return pattern.test(url)
        },
        async initValues() {
            this.tags = this.$attrs.whitelist
        },
        update() {
            this.$http.put(`${laravel.general.root_url}/admin/parameters/tags`, this.tags).then(res => { }).catch(er => {
                console.log(er)
            })
        }
    }
}
</script>