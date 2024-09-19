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
    deposits: Object,
    valuePending: String,
    balance: String,
    create: Boolean
});

</script>

<template>
    <Head title="Deposits" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Deposits</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div>
                    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500">My balance</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ balance }}</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                            <dt class="truncate text-sm font-medium text-gray-500">Deposit pending</dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ valuePending }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Deposits</h1>
                            <p class="mt-2 text-sm text-gray-700">A list of all the deposits in your account including their description, value and status.</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none" v-if="create">
                            <a :href="route('deposit.create')"
                                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Add deposit
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
                            <TableColumnHead class="px-3 py-3.5 text-sm font-semibold text-gray-900 text-center">
                                Status
                            </TableColumnHead>
                            <TableColumnHead class="px-3 py-3.5 text-sm font-semibold text-gray-900 text-right">
                                Value
                            </TableColumnHead>
                        </TableRow>
                        </TableHead>
                        <TableBody>
                            <TableRow v-for="deposit in deposits.data" :key="deposit.id">
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-full">
                                    <div>{{ deposit.description }}</div>
                                    <div class="text-gray-300 text-xs">{{ deposit.created_at }}</div>
                                </TableColumnBody>
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-[120px]">
                                    <div v-if="deposit.status === null" class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        Pending
                                    </div>
                                    <div v-if="deposit.status === false" class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                        Reproved
                                    </div>
                                    <div v-if="deposit.status === true" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        Aproved
                                    </div>
                                </TableColumnBody>
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-0 text-right">
                                    {{ deposit.value }}
                                </TableColumnBody>
                            </TableRow>

                            <TableRow v-if="deposits.data.length === 0">
                                <TableColumnBody colspan="10"
                                                 class-default="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
                                    <div class="text-gray-300 text-xl">No deposits found</div>
                                </TableColumnBody>
                            </TableRow>
                        </TableBody>
                    </TableIndex>

                    <Pagination :pagination="deposits.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
