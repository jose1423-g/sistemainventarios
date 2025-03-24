<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import SearchResult from '@/Components/SearchResult.vue';
import TextArea from '@/Components/TextArea.vue'
import { Head, useForm } from '@inertiajs/vue3';
import {ref, onMounted} from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';

const products = ref([]);

const visibleRight = ref(false);
const showspinner = ref(true);
const btndisabled = ref(false);
const msgerrors = ref([]);
const dataEntrada = ref([]);

const size = ref({ label: 'Small', value: 'small' });

const form = useForm({ 
    id: '',
    searchentrada: '',
    fk_entrada: '',
    Nombre: '',
    Descripcion: '',
    Totalarticulos: '', 
    IdPartida: '',
    Unidad: '',
    Precio: '',
    img: '',
});

const submit = async () => {
    showspinner.value = false;
    btndisabled.value = true; 
    try {
        let resp = await axios.post(route('store.entrada'), form);
        if (resp.data.result == 1) {
            showSuccess(resp.data.msg)
            showspinner.value = true;
            btndisabled.value = false;
            form.reset();
            router.reload({ only: ['Entradas'] });
            msgerrors.value  = [];
        } else {
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

const Edit = async (data) => {
     
    // let resp = await axios.get(route('edit.entrada', data.id));
    
        if (resp.data.result == 0) {
            showError(resp.data.msg);
        } else {
            console.log(resp.data);
            visibleRight.value = true;
            form.id = resp.data.id;
            form.no_orden = resp.data.no_orden;
            form.proveedor = resp.data.proveedor;
            form.fecha_compra = resp.data.fecha_compra;
            form.fecha_entrada = resp.data.fecha_entrada;
            form.area_solicitante = resp.data.area_solicitante;
            form.numero_requisicion = resp.data.numero_requisicion;
            form.cantidad_piezas = resp.data.cantidad_piezas;
            form.precio_unitario = resp.data.precio_unitario;
            form.IVA = resp.data.IVA;
            form.precio_unitario = resp.data.precio_unitario;
            form.Total = resp.data.Total;
            form.searcharea = resp.data.area 
        }
    
}

const Delete  = async (data) => {
    
    // let resp = await axios.delete(route('delete.entrada', data.id));    
    
        if (resp.data.result == 1) {
            showSuccess(resp.data.msg)
            router.reload({ only: ['Entradas'] });
        } else {
            showError(resp.data.msg);            
        }
}

const SearchEntrada = async () => {
    if (form.searchentrada.trim()) {
        try {
            let resp = await axios.get(route('search.partidas', form.searchentrada));            
            dataEntrada.value = resp.data;
        } catch (error) {
            showError(resp.data.msg);
        }        
    } else {
        dataEntrada.value = [];
        form.fk_entrada = '';
    }    
}

const handleSelection = (id, text) => {
    form.fk_entrada = id;
    form.searchentrada = text;
    dataEntrada.value = [];
};

const ClearForm = () => {
    form.reset();
    msgerrors.value  = [];
    visibleRight.value = true;
}

</script>

<template>
    <Head title="Partida presupuestal" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Productos</h3>
            
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
            <DataTable :value="products" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="Code" header="No° orden de compra"></Column>
                <Column field="Name" header="Nombre"></Column>
                <Column field="Name" header="No° Partida"></Column>
                <Column header="Acciones">
                    <template #body="rowdata">
                        <div class="flex gap-2">
                            <PrimaryButton type="button" @click="Edit(rowdata.data)">Editar</PrimaryButton>
                            <PrimaryButton type="button" @click="Delete(rowdata.data)">Eliminar</PrimaryButton>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
        
        <Drawer v-model:visible="visibleRight" header="Entrada de producto" position="right" class="!w-[25rem]">
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />                    
                    <div class="relative">
                        <InputLabel for="NoOrden" value="No° orden de compra"/>
                        <TextInput
                            id="NoOrden"
                            type="search"
                            class="w-full mt-1"
                            placeholder="Buscar..."
                            v-model="form.searchentrada"
                            @input="SearchEntrada"
                        />
                        <SearchResult :data="dataEntrada" :id="'NoOrden'" :label="'id'" :text="'no_orden'" @select="handleSelection" />
                    </div>
                    <TextInput
                        id="fk_entrada"
                        type="text"
                        class="w-full mt-1"
                        v-model="form.fk_entrada"
                    />
                    <div>
                        <InputLabel for="Nombre" value="Nombre"/>
                        <TextInput
                            id="Nombre"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Nombre"
                        />
                    </div>                    
                    <div>
                        <InputLabel for="Descripcion" value="Descripción"/>
                        <TextArea                         
                            id="Descripcion"
                            class="w-full mt-1"
                            v-model="form.Descripcion"
                        />
                    </div>
                    <div>
                        <InputLabel for="Totalarticulos" value="Total de articulos"/>
                        <TextInput
                            id="Totalarticulos"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Totalarticulos"
                        />
                    </div>
                    <div>
                        <InputLabel for="Unidad" value="Unidad"/>
                        <TextInput
                            id="Unidad"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Unidad"
                        />
                    </div>
                    <div>
                        <InputLabel for="Precio" value="Precio"/>
                        <TextInput
                            id="Precio"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Precio"
                        />
                    </div>
                    <div>
                        <InputLabel for="img" value="Imagen"/>
                        <TextInput
                            id="img"
                            type="file"
                            class="w-full mt-1"
                            v-model="form.Precio"
                        />
                    </div>
                    <div class="relative">
                        <InputLabel for="IdPartida" value="No° partida"/>
                        <TextInput
                            id="IdPartida"
                            type="search"
                            class="w-full mt-1"
                            placeholder="Buscar..."
                            v-model="form.IdPartida"
                        />
                        <SearchResult :data="data = []"  :id="'IdPartida'" :label="'Code'" :text="'Name'" :select="handleSelection" />
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
