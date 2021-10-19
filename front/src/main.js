import { createApp } from 'vue/dist/vue.esm-bundler';
import router from '@/router';
import AccountLayout from '@/layouts/Account.vue';
import SidebarItemComponent from '@/components/SidebarItme.vue';
import NavbarItemComponent from '@/components/NavbarItem.vue';
import ModalComponent from '@/components/Modal.vue';
import ParameterComponent from '@/components/Parameter.vue';
import ResourceTableComponent from "@/components/ResourceTable.vue";
import DataTableComponent from "@/components/DataTable.vue";
import VueApexCharts from "vue3-apexcharts";
import "bootstrap";
import "@/assets/index.scss";

const app = createApp({});

app.use(router);
app.use(VueApexCharts);

app.component('account', AccountLayout);
app.component('resource-table', ResourceTableComponent);
app.component('data-table', DataTableComponent);
app.component('sidebar-item', SidebarItemComponent);
app.component('navbar-item', NavbarItemComponent);
app.component('modal', ModalComponent);
app.component('parameter', ParameterComponent);

app.mount('#app');
