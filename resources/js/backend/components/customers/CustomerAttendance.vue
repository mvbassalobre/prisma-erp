<template>
    <div class="row">
        <div class="col-md-2 col-sm-12 pr-0">
            <div
                class="nav flex-column nav-pills"
                id="v-pills-tab"
                role="tablist"
                aria-orientation="vertical"
            >
                <a
                    class="nav-link active mb-2 text-center btn-primary"
                    id="v-pills-info-tab"
                    data-toggle="pill"
                    href="#v-pills-info"
                    role="tab"
                    aria-controls="v-pills-info"
                    aria-selected="true"
                >Cliente</a>
                <a
                    class="nav-link mb-2 text-center btn-primary"
                    id="v-pills-timeline-tab"
                    data-toggle="pill"
                    href="#v-pills-timeline"
                    role="tab"
                    aria-controls="v-pills-timeline"
                    aria-selected="true"
                >Timeline</a>
                <a
                    class="nav-link mb-2 text-center btn-primary"
                    id="v-pills-products-tab"
                    data-toggle="pill"
                    href="#v-pills-products"
                    role="tab"
                    aria-controls="v-pills-products"
                    aria-selected="true"
                >Produtos</a>
            </div>
        </div>
        <div class="col-md-10 col-sm-12">
            <div class="tab-content" id="v-pills-tabContent">
                <comp-info :info="data" />
                <comp-timeline :timeline="customer.timeline" />
                <comp-products :products="customer.products" :customer="customer" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["customer", "data"],
    components: {
        "comp-info": require("./partials/-info").default,
        "comp-timeline": require("./partials/-timeline").default,
        "comp-products": require("./partials/-products").default
    }
}
</script>