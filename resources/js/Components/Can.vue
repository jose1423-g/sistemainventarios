<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'


const props = defineProps({
    roles: { 
        type: Array,
        default: ['Admin', 'Editor']
    }    
})

const page = usePage()

const userRole = computed(() => {
    const auth = page.props.permission?.role?.name || '';
    return auth;
})

const can = computed(() => props.roles.includes(userRole.value));

</script>


<template>
    <slot v-if="can" />
</template>
