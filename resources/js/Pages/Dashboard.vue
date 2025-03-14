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
        mostrar cuanto entro y salio y a que area se mando generar un reporte en general
    </AuthenLayout>
</template>
