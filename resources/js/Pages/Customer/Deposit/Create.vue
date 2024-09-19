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
import {ref} from "vue";

const form = useForm({
    description: '',
    receipt: '',
    value: '',
});

const pending = ref(false);

const submit = async () => {
    pending.value = true;
    const formData = new FormData();
    for (const key in form) {
        formData.append(key, form[key]);
    }

    try {
        await axios.post(route('api.deposit.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        window.location.href = route('deposit.index');
    } catch(error) {
        if (error.response && error.response.status === 422) {
            form.errors = error.response.data.errors;
        }
        pending.value = false;
    }
};

const handleFileChange = (event) => {
    form.receipt = event.target.files[0];
}

</script>

<template>
    <Head title="Deposits" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create deposit</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg gap-4">
                    <div class="flex justify-end">
                        <a :href="route('deposit.index')" class="underline text-gray-400">My deposits</a>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="flex justify-between mt-4 space-x-4">
                            <div class="flex-grow">
                                <InputLabel for="description" value="Description" />

                                <TextInput
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    autocomplete="depositDescription"
                                />

                                <InputError class="mt-2" :message="form.errors.description ? form.errors.description[0] : null" />
                            </div>

                            <div class="flex-grow">
                                <InputLabel for="value" value="Value" />

                                <TextInput
                                    id="value"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    v-model="form.value"
                                    autocomplete="depositValue"
                                />

                                <InputError class="mt-2" :message="form.errors.value ? form.errors.value[0] : null" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="receipt" value="Receipt" />

                            <input type="file"
                                   id="receipt"
                                   ref="receipt"
                                   class="w-1/2"
                                   accept="image/*"
                                   @change="handleFileChange"
                            >

                            <InputError class="mt-2" :message="form.errors.receipt ? form.errors.receipt[0] : null" />
                        </div>

                        <PrimaryButton class="mt-4" :class="{ 'opacity-25': pending }" :disabled="pending">
                            Create
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
