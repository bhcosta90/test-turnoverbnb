<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import TableIndex from '@/Components/Table/Index.vue';
import TableBody from '@/Components/Table/Body.vue';
import TableHead from '@/Components/Table/Head.vue';
import TableRow from '@/Components/Table/Row.vue';
import TableColumnHead from '@/Components/Table/Th.vue';
import TableColumnBody from '@/Components/Table/Td.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    description: '',
    value: '',
});

const submit = async () => {
    await form.post(route('api.order.store'), {
        onSuccess: () => {
            window.location.href = route('desired.route.name');
        },
    });
};

</script>

<template>
    <Head title="Deposits" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create order</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <div class="flex justify-end">
                        <a :href="route('order.index')" class="underline text-gray-400">My orders</a>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="flex justify-between space-x-4">
                            <div class="flex-grow">
                                <InputLabel for="description" value="Description" />

                                <TextInput
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    autocomplete="orderDescription"
                                />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="flex-grow">
                                <InputLabel for="value" value="Value" />

                                <TextInput
                                    id="value"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    v-model="form.value"
                                    autocomplete="orderValue"
                                />

                                <InputError class="mt-2" :message="form.errors.value" />
                            </div>
                        </div>

                        <PrimaryButton class="mt-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Create
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
