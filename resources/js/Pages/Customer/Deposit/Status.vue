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
});

const aprove = (id) => {
    if(confirm('Are you sure you want to approve this deposit?')) {
        updateDeposit(id, 1);
    }
};

const reprove = (id) => {
    if(confirm('Are you sure you want to reprove this deposit?')) {
        updateDeposit(id, 0);
    }
};

const updateDeposit = (id, status) => {
    axios.post(route('deposit.update', id), {
        status: status
    }).then(response => {
        if(response.data.status === 1) {
            alert('Deposit approved successfully');
        } else {
            alert('Deposit reproved successfully');
        }
        window.location.reload();
    }).catch(error => {
        alert('An error occurred while trying to update the deposit');
    });
};

</script>

<template>
    <Head title="Deposits" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Deposits</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Deposits</h1>
                            <p class="mt-2 text-sm text-gray-700">A list of all the deposits pending approval.</p>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <TableIndex>
                        <TableHead>
                        <TableRow>
                            <TableColumnHead>
                                Description
                            </TableColumnHead>
                            <TableColumnHead />
                        </TableRow>
                        </TableHead>
                        <TableBody>
                            <TableRow v-for="deposit in deposits.data" :key="deposit.id">
                                <TableColumnBody class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-full">
                                    <div>{{ deposit.description }}</div>
                                    <div class="text-gray-300 text-xs">{{ deposit.created_at }}</div>
                                </TableColumnBody>
                                <TableColumnBody>
                                    <a :href="route('deposit.edit', deposit.id)"
                                       class="rounded-full bg-indigo-600 p-1 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Show
                                    </a>
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
