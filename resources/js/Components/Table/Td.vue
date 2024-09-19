<script setup>
defineProps({
    classDefault: {
        type: String,
        default: 'whitespace-nowrap px-3 py-4 text-sm text-gray-500'
    },
    colspan: {
        type: String,
        default: null
    }
});
</script>
<template>
    <td :class="classDefault" :colspan="colspan">
        <slot />
    </td>
</template>
