<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';


</script>

<template>
    <Head title="Partida Presupuestal" />

    <AuthenLayout>
        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Administracion de roles de usuarios</h3>
            <h4 class="mb-5 font-bold text-gray-900">Asignar Roles</h4>
            <div class="flex items-center space-x-5 space-between">
                <InputLabel for="usuario" value="Usuario"/>
                <TextInput />
                <InputLabel for="rol" value="Rol"/>
                <Select />
                <PrimaryButton>Asignar Rol</PrimaryButton>
            </div>
            <h4 class="mt-5 mb-5 font-bold text-gray-900">Remover Roles</h4>
            <div class="flex items-center mb-5 space-x-5 space-between">
                <InputLabel for="usuario" value="Usuario"/>
                <TextInput />
                <InputLabel for="rol" value="Rol"/>
                <Select />
                <PrimaryButton>Remover Rol</PrimaryButton>
            </div>
        </div>
    </AuthenLayout>
</template>
