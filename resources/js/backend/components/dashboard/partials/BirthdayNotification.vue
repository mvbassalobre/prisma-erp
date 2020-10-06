<template>
  <div
    class="alert alert-info alert-dismissible fade show"
    role="alert"
    v-show="visible"
  >
    <b class="mr-2">
      <span class="el-icon-present mr-2" />
      Aviso de Aniversariantes!
    </b>
    Neste mês
    <template v-if="countCustomers > 0">
      {{ countCustomers }}
      {{ `${countCustomers > 1 ? 'clientes' : 'cliente'}` }}
    </template>
    <template v-if="countUsers > 0">
      <template v-if="countCustomers > 0 && countUsers > 0"> e </template>
      {{ countUsers }}
      {{ `${countUsers > 1 ? 'colaboradores' : 'colaborador'}` }}
    </template>
    <template v-if="countUsers + countCustomers > 1"> farão </template>
    <template v-else> fará </template>
    aniversário.
    <a class="ml-4" href="/admin/birthdays" v-if="hide_link == undefined"
      >Clique aqui para ver mais detalhes</a
    >
    <button
      type="button"
      class="close"
      data-dismiss="alert"
      aria-label="Close"
      v-if="hide_link == undefined"
    >
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>
<script>
export default {
  props: ['hide_link'],
  data: () => ({
    attempts: 0,
    data: [],
  }),
  created() {
    this.init()
  },
  computed: {
    visible() {
      return this.countUsers + this.countCustomers > 0
    },
    countCustomers() {
      return this.data.filter((i) => i.resource == 'customers').length
    },
    countUsers() {
      return this.data.filter((i) => i.resource == 'users').length
    },
  },
  methods: {
    init() {
      this.attempts++
      this.$http
        .post(`/admin/birthdays/get-counter-month`, {})
        .then((resp) => {
          this.data = resp.data
        })
        .catch((er) => {
          if (this.attempts <= 3) return this.init()
          console.log(er)
        })
    },
  },
}
</script>