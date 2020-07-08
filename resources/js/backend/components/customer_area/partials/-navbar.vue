<template>
    <nav
        class="navbar navbar-expand-lg navbar-light bg-light justify-content-between align-items-center"
    >
        <div class="collapse navbar-collapse d-flex align-items-center">
            <div class="container-fluid px-0">
                <ul class="navbar-nav d-none d-none d-lg-block py-2">
                    <img :src="logo" style="height: 40px;" />
                </ul>
                <ul class="navbar-nav d-none d-none d-lg-block ml-auto">
                    <li class="nav-item dropdown">
                        <a
                            href="#"
                            id="navbarDropdownMenuLink"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="nav-link dropdown-toggle d-flex align-items-center flex-row"
                        >
                            <b class="mr-2">{{customer.email}}</b>
                        </a>
                        <div
                            aria-labelledby="navbarDropdownMenuLink"
                            class="dropdown-menu dropdown-menu-right"
                        >
                            <a href="#" @click.prevent="logout" class="dropdown-item">
                                <span class="el-icon-close mr-2 font-weight-bolder"></span>Sair
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    props: ["customer", "logo"],
    methods: {
        logout() {
            this.$confirm("Deseja sair ?", "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: 'warning'
            }).then(() => {
                Cookies.remove("customer_area_user")
                this.$parent.$parent.logged = false
                this.$parent.$parent.customer = {}
            })
        }
    }
}
</script>