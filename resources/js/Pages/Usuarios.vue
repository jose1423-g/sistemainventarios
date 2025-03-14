<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';

const visible = ref(false);

</script>

<template>
    <Head title="Partida Presupuestal" />

    <AuthenLayout>
        Crear y editar usuarios
        y darle su rol o permiso
    </AuthenLayout>
</template>
