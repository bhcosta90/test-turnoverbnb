<script setup>
defineProps({
    classDefault: {
        type: String,
        default: 'px-3 py-3.5 text-left text-sm font-semibold text-gray-900'
    }
});
</script>

<template>
    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
        <slot />
    </th>
</template>
