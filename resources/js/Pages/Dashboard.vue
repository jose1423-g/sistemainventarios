<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';

import Drawer from 'primevue/drawer';
import Button from 'primevue/button';

const visible = ref(false);

</script>

<template>
    <Head title="Partida Presupuestal" />

    <AuthenLayout>


        Mostrar los productos que se han movido de lugar o que se han solicitado, ingresos de productos por fecha.

        <div class="flex justify-center card">
            <Drawer v-model:visible="visible" header="Drawer">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </Drawer>
            <Button icon="pi pi-arrow-right" @click="visible = true" />
        </div>
    </AuthenLayout>
</template>
