<script setup>
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import Can from '@/Components/Can.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import FieldError from '@/Components/FieldError.vue';
const toast = useToast();

const props = defineProps({
    partida:{
        type:Array,
        default: []
    }
});

const size = ref({ label: 'Small', value: 'small' });

const visibleRight = ref(false);
const showspinner = ref(true);
const btndisabled = ref(false);
const msgerrors = ref ([]);

const form = useForm({ 
    id: '',
    no_partida: '',
    nombre: '',
    descripcion: '',
});

const formsearch = useForm({
    search_nopartida: '',
    search_nombre: '',
});

const partidas = ref(props.partida);

watch(() => props.partida, (newVal) => {
    partidas.value = [...newVal];
});

const SearchPartidasTable = async () => {
    try {
        let resp = await axios.post(route('search.partida.table'), formsearch);
        partidas.value = resp.data;
    } catch (error) {
        showError(error.data.msg)
    }
}

const ClearFormPartidas = async () => {

    formsearch.search_nopartida = '';
    formsearch.search_nombre = '';    

    await SearchPartidasTable();
}

const submit = async () => {
    showspinner.value = false;
    btndisabled.value = true;

    try {
        let resp = await axios.post(route('store.partida'), form);
        if(resp.data.result == 1){
            showSuccess(resp.data.msg)
            showspinner.value = true;
            btndisabled.value = false;
            form.reset();
            router.reload({only:['partida']});
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

const showSuccess = (msg) => {
    toast.add({ severity: 'success', summary: 'Success', detail: msg, life: 3000 });
};
const showError = (msg) => {
    toast.add({ severity: 'error', summary: 'Error', detail: msg, life: 3000 });
};

const Edit  = async (data) =>{
    let resp = await axios.get(route('get.partida', data.id));

    if(resp.data.result == 0){
        showError(resp.data.msg);
    }else{
        visibleRight.value = true;
        form.id = resp.data.id;
        form.no_partida = resp.data.no_partida;
        form.nombre = resp.data.nombre;
        form.descripcion = resp.data.descripcion;
    }
}

const Delete = async (data) =>{
    if (confirm('¿Estas seguro de eliminar este registro?')) {

        let resp = await axios.delete(route('delete.partida', data.id));
        
        if(resp.data.result == 1){
            showSuccess(resp.data.msg);
            router.reload({only:['partida']});
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
    <Head title="Partida presupuestal" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-2xl font-bold text-gray-900">Partida Presupuestal</h3>
                <PrimaryButton type="button" @click="ClearForm">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Agregar</span>                    
                    </div>
                </PrimaryButton>
            </div>
            <!-- buscador -->
            <div>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-3">
                    <div>
                        <InputLabel for="search_nopartida" value="No° de partida"/>
                        <TextInput
                            id="search_nopartida"
                            type="search"
                            class="w-full mt-1"
                            v-model="formsearch.search_nopartida"
                        />
                    </div>
                    <div>
                        <InputLabel for="search_nombre" value="Nombre"/>
                        <TextInput
                            id="search_nombre"
                            type="search"
                            class="min-w-full mt-1"
                            v-model="formsearch.search_nombre"
                        />
                    </div>
                </div>                
                <div class="flex justify-end space-x-3 mb-5">
                    <SecondaryButton type="button" @click="ClearFormPartidas">Clear</SecondaryButton>
                    <PrimaryButton type="button" @click="SearchPartidasTable">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                        </svg>
                    </PrimaryButton>
                </div>
            </div>
                        
            <DataTable :value="partidas" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="no_partida" header="No° Partida"></Column>
                <Column field="nombre" header="nombre"></Column>
                <Column header="Acciones">
                    <template #body="rowdata">
                        <div class="flex gap-2">
                            <Can :roles="['Admin', 'Editor']">
                                <PrimaryButton type="button" @click="Edit(rowdata.data)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                    </svg>
                                </PrimaryButton>
                            </Can>
                            <Can :roles="['Admin']">
                                <DangerButton type="button" @click="Delete(rowdata.data)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                    </svg>
                                </DangerButton>
                            </Can>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>        

    <Toast />
        <Drawer v-model:visible="visibleRight" header="Partida Presupuestal" position="right">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />
                    <div>
                        <InputLabel for="no_partida" value="No° de partida"/>
                        <TextInput                         
                            id="no_partida"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.no_partida"
                        />
                        <FieldError :message="msgerrors.no_partida"/>
                    </div>
                    <div>
                        <InputLabel for="nombre" value="Nombre de la partida"/>
                        <TextInput                         
                            id="nombre"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.nombre"
                        />
                        <FieldError :message="msgerrors.nombre"/>
                    </div>
                    <div>
                        <InputLabel for="descripcion" value="Descripción"/>
                        <TextArea                         
                            id="descripcion"
                            class="w-full mt-1"
                            v-model="form.descripcion"
                        />
                        <FieldError :message="msgerrors.descripcion"/>
                    </div>                    
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
