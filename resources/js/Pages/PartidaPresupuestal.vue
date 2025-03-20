<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import FieldError from '@/Components/FieldError.vue';
const toast = useToast();

defineProps({
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
            showSucces(msg)
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

const editProduct  = (holis) => {
    console.log(holis);
}

</script>

<template>
    <Head title="Partida presupuestal" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Partida Presupuestal</h3>
            
            <div class="flex justify-end mb-5">
                <PrimaryButton type="button" @click="visibleRight = true">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Agregar</span>                    
                    </div>
                </PrimaryButton>
            </div>
                        
            <DataTable :value="partida" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="no_partida" header="No° Partida"></Column>
                <Column field="nombre" header="nombre"></Column>
                <Column header="Acciones">
                    <template #body="rowdata">
                        <div class="flex gap-2">
                            <PrimaryButton type="button" @click="editProduct(rowdata.data)">Editar</PrimaryButton>
                            <PrimaryButton type="button" @click="editProduct(rowdata.data)">Eliminar</PrimaryButton>
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
