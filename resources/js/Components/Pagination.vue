<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    pagination: Object,
})
</script>


<template>
    <nav class="relative flex items-center justify-between">
        <Link
            preserve-scroll
            :href="pagination.prev ?? ''"
            class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600"
            :class="{ 'bg-gray-200': pagination.prev, '!text-gray-300': !pagination.prev }"
        >
            Previous
        </Link>

        <Link
            preserve-scroll
            :href="pagination.next ?? ''"
            class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600"
            :class="{ 'bg-gray-200': pagination.next, '!text-gray-300': !pagination.next }"
        >
            Next
        </Link>
    </nav>
</template>
