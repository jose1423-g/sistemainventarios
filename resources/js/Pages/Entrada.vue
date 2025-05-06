<script setup>
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import Can from '@/Components/Can.vue';
import SearchResult from '@/Components/SearchResult.vue';
import FieldError from '@/Components/FieldError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import {ref, watch} from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
const toast = useToast();

const visibleRight = ref(false);
const showspinner = ref(true);
const btndisabled = ref(false);
const dataArea = ref([]);
const msgerrors = ref([]);

const searcharea = ref(''); 
const banderaarea = ref(false);

const size = ref({ label: 'Small', value: 'small' });

const props = defineProps({
    Entradas: {
        type: Array,
    },
    Areas: {
        type: Array,
    },
})

const form = useForm({ 
    id: '',
    no_orden: '',
    proveedor: '',
    fecha_compra: '',
    fecha_entrada: '',
    area_solicitante: '',
    numero_requisicion: '',
    cantidad_piezas: '',
    precio_unitario: '',
    IVA: '',
    total: '',
});

const formsearch = useForm({
    search_noentrada: '',
    search_fechacompra: '',
    search_fechaentrada: '',
    search_area: '',
});

const entradas = ref(props.Entradas);

watch(() => props.Entradas, (newVal) => {
    entradas.value = [...newVal];
});

const SearchEntradaTable = async () => {
    try {
        let resp = await axios.post(route('search.entrada.table'), formsearch);
        entradas.value = resp.data;
    } catch (error) {
        showError(error.data.msg)
    }
}

const ClearFormEntrada = async () => {

    formsearch.search_noentrada = '';
    formsearch.search_fechacompra = '';
    formsearch.search_fechaentrada = '';
    formsearch.search_area = '';  

    await SearchEntradaTable();

}

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
     
    let resp = await axios.get(route('edit.entrada', data.id));
    
        if (resp.data.result == 0) {
            showError(resp.data.msg);
        } else {
            
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
            form.total = resp.data.total;
            searcharea.value = resp.data.area 

            banderaarea.value = true;

            setTimeout( () => {
                banderaarea.value = false;
            }, 100);
        }
    
}

const Delete  = async (data) => {
    
    if (confirm('¿Estas seguro de eliminar este registro?')) {

        let resp = await axios.delete(route('delete.entrada', data.id));    
    
        if (resp.data.result == 1) {
            showSuccess(resp.data.msg)
            router.reload({ only: ['Entradas'] });
        } else {
            showError(resp.data.msg);            
        }
    } else {
        return false;
    }
}

const SearchArea = async (timeouarea) => {
    if (timeouarea) {
        try {
            let resp = await axios.get(route('search.area', timeouarea));            
            dataArea.value = resp.data;
        } catch (error) {
            dataArea.value = error.response.data;
        }
    } else {
        dataArea.value = [];
        form.area_solicitante = '';
        banderaarea.value = false;
    }    
}

const handleSelectionarea = (id, text) => {  
    banderaarea.value = true;  
    form.area_solicitante = id;
    searcharea.value = text;
    dataArea.value = [];

    setTimeout( () => {
        banderaarea.value = false;
    }, 100);
};

let timeouarea = null;
watch(searcharea, (newvalue) => {
    if (banderaarea.value) return false;
    
    clearTimeout(timeouarea);
    timeouarea = setTimeout(() => {
        SearchArea(newvalue)
    }, 500);
});

const ClearForm = () => {
    form.reset();
    msgerrors.value  = [];
    visibleRight.value = true;
    searcharea.value = '';
}

</script>

<template>
    <Head title="Entrada" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-2xl font-bold text-gray-900">Entradas</h3>
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
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-3">
                <div>
                    <InputLabel for="search_noentrada" value="No° entrada"/>
                    <TextInput
                        id="search_noentrada"
                        type="search"
                        class="w-full mt-1"
                        v-model="formsearch.search_noentrada"
                    />
                </div>
                <div>
                    <InputLabel for="search_fechacompra" value="Fecha de compra"/>
                    <TextInput
                        id="search_fechacompra"
                        type="date"
                        class="min-w-full mt-1"
                        v-model="formsearch.search_fechacompra"
                    />
                </div>
                <div>
                    <InputLabel for="search_fechaentrada" value="Fecha de entrada"/>
                    <TextInput
                        id="search_fechaentrada"
                        type="date"
                        class="w-full mt-1"
                        v-model="formsearch.search_fechaentrada"
                    />
                </div>
                <div>
                    <InputLabel for="search_area" value="Area"/>
                    <Select
                        id="search_area"                            
                        class="w-full mt-1"
                        :data="Areas"
                        :label="'id'"
                        :text="'area'"
                        v-model="formsearch.search_area"

                    />
                </div>
            </div>                
            <div class="flex justify-end space-x-3 mb-5">
                <SecondaryButton type="button" @click="ClearFormEntrada">Clear</SecondaryButton>
                <PrimaryButton type="button" @click="SearchEntradaTable">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                    </svg>
                </PrimaryButton>
            </div>            
            <DataTable :value="entradas" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="no_orden" header="No° Entrada"></Column>
                <Column field="fecha_compra" header="Fecha de compra"></Column>
                <Column field="fecha_entrada" header="Fecha de entrada"></Column>
                <Column field="area" header="Area solicitante"></Column>
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
        <!-- alerta -->
        <Toast />
        
        <Drawer v-model:visible="visibleRight" header="Entrada de producto" position="right" class="!w-[25rem]">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />                    
                    <div>
                        <InputLabel for="no_orden" value="No° orden de compra"/>
                        <TextInput
                            id="no_orden"
                            type="text"
                            class="w-full mt-1"                            
                            v-model="form.no_orden"
                        />
                        <FieldError :message="msgerrors.no_orden" />
                    </div>
                    <div>
                        <InputLabel for="proveedor" value="Proveedor"/>
                        <TextInput
                            id="proveedor"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.proveedor"
                        />
                        <FieldError :message="msgerrors.proveedor" />
                    </div>
                    <div>
                        <InputLabel for="fecha_compra" value="Fecha de compra"/>
                        <TextInput
                            id="fecha_compra"
                            type="date"
                            class="w-full mt-1"
                            v-model="form.fecha_compra"
                        />
                        <FieldError :message="msgerrors.fecha_compra" />
                    </div>
                    <div>
                        <InputLabel for="fecha_entrada" value="Fecha de entrada"/>
                        <TextInput
                            id="fecha_entrada"
                            type="date"
                            class="w-full mt-1"
                            v-model="form.fecha_entrada"                            
                        />
                    </div>
                    <div class="relative">
                        <InputLabel for="searcharea" value="Area solicitante"/>
                        <TextInput
                            id="searcharea"
                            type="search"
                            class="w-full mt-1"
                            placeholder="Buscar..."
                            v-model="searcharea"
                        />
                        <SearchResult v-if="searcharea" :id="'searcharea'" :data="dataArea" :label="'id'" :text="'area'" @select="handleSelectionarea" />
                        <FieldError :message="msgerrors.area_solicitante" />
                    </div>  
                    <TextInput
                        id="area_solicitante"
                        type="hidden"
                        class="w-full mt-1"
                        v-model="form.area_solicitante"                            
                    />
                    <div>
                        <InputLabel for="numero_requisicion" value="No° requisición"/>
                        <TextInput
                            id="numero_requisicion"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.numero_requisicion"
                        />
                        <FieldError :message="msgerrors.numero_requisicion" />
                    </div>
                    <div>
                        <InputLabel for="cantidad_piezas" value="Cantidad/Piezas"/>
                        <TextInput
                            id="cantidad_piezas"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.cantidad_piezas"
                        />
                        <FieldError :message="msgerrors.cantidad_piezas" />
                    </div>
                    <div>
                        <InputLabel for="precio_unitario" value="Precio Unitario"/>
                        <TextInput
                            id="precio_unitario"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.precio_unitario"
                        />
                        <FieldError :message="msgerrors.precio_unitario" />
                    </div>                    
                    <div>
                        <InputLabel for="IVA" value="IVA"/>
                        <TextInput
                            id="IVA"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.IVA"
                        />
                        <FieldError :message="msgerrors.IVA" />
                    </div>                    
                    <div>
                        <InputLabel for="total" value="total"/>
                        <TextInput
                            id="total"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.total"
                        />
                        <FieldError :message="msgerrors.total" />
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
