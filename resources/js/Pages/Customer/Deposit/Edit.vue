<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import axios from 'axios';

defineProps({
    deposit: Object,
});

const aprove = (id) => {
    if (confirm('Are you sure you want to approve this deposit?')) {
        updateDeposit(id, 1);
    }
};

const reprove = (id) => {
    if (confirm('Are you sure you want to reprove this deposit?')) {
        updateDeposit(id, 0);
    }
};

const updateDeposit = (id, status) => {
    axios.patch(route('api.deposit.status', id), {
        status: status
    }).then(response => {
        if (response.data.data.status === true) {
            alert('Deposit approved successfully');
        } else {
            alert('Deposit reproved successfully');
        }
        window.location.href = route('deposit.status');
    }).catch(error => {
        alert('An error occurred while trying to update the deposit');
    });
};
</script>

<template>
    <Head title="Deposits"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create deposit</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <form @submit.prevent="submit">
                        <div class="mt-4">
                            <InputLabel for="description" value="Description"/>
                            <div class="text-2xl">{{ deposit.data.description }}</div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="value" value="Value"/>
                            <div class="text-2xl">{{ deposit.data.value }}</div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="receipt" value="Receipt"/>
                            <img :alt="deposit.data.description" :src="deposit.data.receipt"/>
                        </div>

                        <div class="flex justify-between mt-4">
                            <PrimaryButton @click="aprove(deposit.data.id)">Aprove</PrimaryButton>
                            <SecondaryButton @click="reprove(deposit.data.id)">Reproved</SecondaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
