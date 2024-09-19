<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TableIndex from '@/Components/Table/Index.vue';
import TableBody from '@/Components/Table/Body.vue';
import TableHead from '@/Components/Table/Head.vue';
import TableRow from '@/Components/Table/Row.vue';
import TableColumnHead from '@/Components/Table/Th.vue';
import TableColumnBody from '@/Components/Table/Td.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    orders: Object,
    valuePending: String,
    balance: String,
    create: Boolean,
});

</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Orders</h1>
                            <p class="mt-2 text-sm text-gray-700">A list of all the Orders in your account including their description, value.</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none" v-if="create">
                            <a :href="route('order.create')"
                                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Add order
                            </a>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <TableIndex>
                        <TableHead>
                        <TableRow>
                            <TableColumnHead>
                                Description
                            </TableColumnHead>
                            <TableColumnHead class="px-3 py-3.5 text-sm font-semibold text-gray-900 text-right">
                                Value
                            </TableColumnHead>
                        </TableRow>
                        </TableHead>
                        <TableBody>
                            <TableRow v-for="order in orders.data" :key="order.id">
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-full">
                                    <div>{{ order.description }}</div>
                                    <div class="text-gray-300 text-xs">{{ order.created_at }}</div>
                                </TableColumnBody>
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-0 text-right">
                                    {{ order.value }}
                                </TableColumnBody>
                            </TableRow>

                            <TableRow v-if="orders.data.length === 0">
                                <TableColumnBody colspan="10"
                                                 class-default="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
                                    <div class="text-gray-300 text-xl">No orders found</div>
                                </TableColumnBody>
                            </TableRow>
                        </TableBody>
                    </TableIndex>

                    <Pagination :pagination="orders.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
