<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import FieldError from '@/Components/FieldError.vue';
const toast = useToast();

 defineProps({
    users:{
        type:Array,
        default: []
    },
    roles:{
        type:Array,
        default: []
    }
});

const size = ref({ label: 'Small', value: 'small' });

const visibleRight = ref(false);
const showspinner = ref(true);
const btndisabled = ref(false);
const msgerrors = ref ([]);

const showSuccess = (msg) => {
    toast.add({ severity: 'success', summary: 'Success', detail: msg, life: 3000 });
};
const showError = (msg) => {
    toast.add({ severity: 'error', summary: 'Error', detail: msg, life: 3000 });
};

const form = useForm({ 
    id: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    fk_rol: '', 
});

const submit = async () => {
    showspinner.value = false;
    btndisabled.value = true;

    try {
        let resp = await axios.post(route('store.usuario'), form);
        if(resp.data.result == 1){
            showSuccess(resp.data.msg)
            showspinner.value = true;
            btndisabled.value = false;
            form.reset();
            router.reload({only:['users']});
            msgerrors.value = [];
        }else{
            showError(resp.data.msg)
            showspinner.value = true;
            btndisabled.value = false;
        }
    } catch (error) {
        showspinner.value = true;
        btndisabled.value = false;
        msgerrors.value = error.response.data.errors;
    }
}

const Edit = async (data) => {
    let resp = await axios.get(route('edit.usuario', data.id));
    if(resp.data.result == 0){
        showError(resp.data.msg);
        }
        else{
            form.id = data.id;
            form.name = data.name;
            form.email = data.email;
            form.password = '';
            form.password_confirmation = '';
            visibleRight.value = true;
        }
}

const Delete = async (data) => {
    if (confirm('¿Estas seguro de eliminar este registro?')) {
        
        let resp = await axios.delete(route('delete.usuario', data.id));
        if(resp.data.result == 1){
            showSuccess(resp.data.msg);
            router.reload({only:['users']});
        }else{
            showError(resp.data.msg);
        }

    } else {
        return false;
    }
}

const ClearForm = () => {
    form.reset();
    msgerrors.value = [];
    visibleRight.value = true;
}
</script>

<template>
    <Head title="Usuarios" />

    <AuthenLayout>
        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Administracion de roles de usuarios</h3>
            <div class="flex justify-end mb-5">
                <PrimaryButton type="button" @click="ClearForm">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Agregar</span>                    
                    </div>
                </PrimaryButton>
            </div>
                        
            <DataTable :value="users" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="nombre_rol" header="Roles"></Column>
                <Column field="name" header="Nombre"></Column>
                <Column field="email" header="Correo"></Column>
                <Column header="Acciones">
                    <template #body="rowdata">
                        <div class="flex gap-2">
                            <template v-if="$page.props.role.role_user.role == 'Admin'">
                                <PrimaryButton type="button" @click="Edit(rowdata.data)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                    </svg>
                                </PrimaryButton>
                            </template>
                            <template v-if="$page.props.role.role_user.role == 'Admin'">
                                <DangerButton type="button" @click="Delete(rowdata.data)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                    </svg>
                                </DangerButton>
                            </template>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>        

    <Toast />
        <Drawer v-model:visible="visibleRight" header="Roles de usuarios" position="right">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />
                    <div>
                    <InputLabel for="name" value="Nombre"/>
                    <TextInput 
                    id="name"
                    type="text"
                    class="w-full mt-1"
                    v-model="form.name"
                    />
                    <FieldError :message="msgerrors.name" />
                    </div>
                    <div>
                    <InputLabel for="email" value="Email"/>
                        <TextInput
                            id="email"
                            type="email"
                            class="w-full mt-1"
                            v-model="form.email"
                        />
                        <FieldError :message="msgerrors.email" />
                    </div>
                    <div>
                        <InputLabel for="password" value="Contraseña"/>
                        <TextInput
                            id="password"
                            type="password"
                            class="w-full mt-1"
                            v-model="form.password"
                        />
                        <FieldError :message="msgerrors.password" />
                        </div>
                        <div>
                        <InputLabel for="password_confirmation" value="Confirmar contraseña"/>
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="w-full mt-1"
                            v-model="form.password_confirmation"
                        />
                        <FieldError :message="msgerrors.password_confirmation" />
                        </div>
                        <InputLabel for="fk_rol" value="Asignar rol"/>
                        <Select
                            id="fk_rol"
                            class="w-full mt-1"
                            v-model="form.fk_rol"
                            :data="roles"
                            :label="'id'"
                            :text="'nombre_rol'"
                        />
                        <FieldError :message="msgerrors.fk_rol" />              
                </div>                
                
                <div class="flex justify-end mt-5">
                    <PrimaryButton type="submit" :Show="showspinner" :Disabled="btndisabled" class="w-full"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>guardar</span>
                    </PrimaryButton>
                </div>
            </form>
        </Drawer>
    </AuthenLayout>
</template>