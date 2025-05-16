<script setup>
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import Modal from '@/Components/Modal.vue';
import Can from '@/Components/Can.vue'
import SearchResult from '@/Components/SearchResult.vue';
import FieldError from '@/Components/FieldError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import {ref, watch} from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
const toast = useToast();

const props = defineProps({
    Salidas: {
        type: Array,
        default: [],
    },
    Areas: {
        type: Array,
        default: [],
    },
});

const msgerrors = ref([]);

const dataProducto = ref([]);
const searchproducto = ref('');
const banderaproducto = ref(false);
const Cantidad = ref('');
const producto_id = ref(null); 

const dataCompra = ref([]);
const searchcompra = ref('');
const banderacompra = ref(false);

const dataArea = ref([]);
const searcharea = ref('');
const banderaarea = ref(false);

const showspinner = ref(true);
const btndisabled = ref(false);
const showmodal = ref(false);

const size = ref({ label: 'Small', value: 'small' });

const closeModal = () => {    
    showmodal.value = false;
    searchproducto.value = ''
    searcharea.value = '';
    searchcompra.value = '';
    Cantidad.value = '';
    form.reset();
    msgerrors.value  = [];
}

const form = useForm({ 
    id: '',
    no_salida: '',
    fk_no_compra: '',
    fecha_salida: '',
    fk_area: '',
    personal: '',
    Productos: [],
});

const formsearch = useForm({
    search_nosalida: '',
    search_noentrada: '',
    search_fechasalida: '',
    search_area: '',
});

const salidas = ref(props.Salidas);

watch(() => props.Salidas, (newVal) => {
    salidas.value = [...newVal];
});

const SearchSalidaTable = async () => {
    try {
        let resp = await axios.post(route('search.salida.table'), formsearch);
        salidas.value = resp.data;
    } catch (error) {
        showError(error.data.msg)
    }
}

const ClearFormSalida = async () => {

    formsearch.search_nosalida = '';
    formsearch.search_noentrada = '';
    formsearch.search_fechasalida = '';
    formsearch.search_area = '';  

    await SearchSalidaTable();
}

const submit = async () => {
    let timetoast = null;
    showspinner.value = false;
    btndisabled.value = true; 
    
    try {
        let resp = await axios.post(route('store.salida'), form);
        if (resp.data.result == 1) {
            showmodal.value = false;            
            clearTimeout(timetoast);
            timetoast = setTimeout(() => {
                showSuccess(resp.data.msg);
            }, 500);
            searcharea.value = '';
            searchcompra.value = '';
            searchproducto.value = '';
            showspinner.value = true;
            btndisabled.value = false;
            form.reset();
            router.reload({ only: ['Salidas'] });
            msgerrors.value  = [];
        } else {            
            if (resp.data.result == 3) {
                msgerrors.value = resp.data
                showspinner.value = true;
                btndisabled.value = false;
                return false;
            }
            
            clearTimeout(timetoast);
            timetoast = setTimeout(() => {
                showError(resp.data.msg);
            }, 500);
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
    let resp = await axios.get(route('edit.salida', data.id));
    if (resp.data.result == 0) {
        showError(resp.data.msg);
    } else {
        showmodal.value = true;
        form.id = resp.data.salida.id;
        form.no_salida = resp.data.salida.no_salida;
        form.fk_no_compra = resp.data.salida.id_orden;
        searchcompra.value = resp.data.salida.no_orden;
        form.fecha_salida = resp.data.salida.fecha_salida;
        form.fk_area = resp.data.salida.id_area;
        searcharea.value = resp.data.salida.area
        form.personal = resp.data.salida.personal;

        let dataproductos = resp.data.productos_salida;

        dataproductos.forEach(element => {
            let nuevoProducto = {
                id: element.fk_producto,
                nombre: element.nombre,
                cantidad: element.cantidad
            };
            form.Productos.push(nuevoProducto);     
        });
        
        banderacompra.value = true;
        banderaarea.value = true;
        setTimeout( () => {                    
            banderacompra.value = false;
            banderaarea.value = false;
        }, 100);
    }
}

const Delete = async (data) => {

    if (confirm('¿Estas seguro de eliminar este registro?')) {

        let resp = await axios.delete(route('delete.salida', data.id));
        
        if (resp.data.result == 1) {
            showSuccess(resp.data.msg)
            router.reload({ only: ['Salidas'] });
        } else {
            showError(resp.data.msg);            
        }

    } else {        
        return false;
    }
}

const SearchArea = async (newarea) => {
    if (newarea) {
        try {
            let resp = await axios.get(route('search.area', newarea));                        
            dataArea.value = resp.data;
        } catch (error) {
            dataArea.value = error.response.data;
        }
    } else {
        dataArea.value = [];
        form.fk_area = '';
        banderaarea.value = false;
    }
}

let timeoutarea = null;
watch(searcharea, (newvalue) => {
    if (banderaarea.value) return false;
    
    clearTimeout(timeoutarea);
    timeoutarea = setTimeout(() => {
        SearchArea(newvalue)
    }, 500);
});

const handleSelectionArea = (id, text, item) => {
    banderaarea.value = true;
    form.fk_area = id;
    form.personal = item.nombre;
    searcharea.value = text;
    dataArea.value = [];

    setTimeout( () => {
        banderaarea.value = false;
    }, 100);
};

const SearchCompra = async (newcompra) => {
    if (newcompra) {
        try {
            let resp = await axios.get(route('search.entradas', newcompra));                        
            dataCompra.value = resp.data;
        } catch (error) {
            dataCompra.value = error.response.data;
        }
    } else {
        dataCompra.value = [];
        form.fk_no_compra = '';
        banderacompra.value = false;
    }    
}

let timeoutcompra = null;
watch(searchcompra, (newvalue) => {
    if (banderacompra.value) return false;
    
    clearTimeout(timeoutcompra);
    timeoutcompra = setTimeout(() => {
        SearchCompra(newvalue)
    }, 500);
});

const handleSelectionCompra = (id, text) => {
    banderacompra.value = true;
    form.fk_no_compra = id;
    searchcompra.value = text;
    dataCompra.value = []; 

    setTimeout( () => {
        banderacompra.value = false;
    }, 100);
};

const SearchProductos = async (newproduct) => {
    if (newproduct) {
        try {
            let resp = await axios.get(route('search.productos', newproduct));                        
            dataProducto.value = resp.data;
        } catch (error) {
            dataProducto.value = error.response.data;
        }
    } else {
        dataProducto.value = [];
        form.id = '';
        banderaproducto.value = false;
    }    
}

const handleSelectionProducto = (id, text) => {
    banderaproducto.value = true;
    producto_id.value = id;    
    searchproducto.value = text;
    dataProducto.value = [];

    setTimeout( () => {
        banderaproducto.value = false;
    }, 100);
};

let timeoutproduct = null;
watch(searchproducto, (newvalue) => {
    if (banderaproducto.value) return false;
    
    clearTimeout(timeoutproduct);
    timeoutproduct = setTimeout(() => {
        SearchProductos(newvalue)
    }, 500);
});

const Removeproduct = (index) => {
    form.Productos.splice(index, 1)
}

const addProduct = () => {

    if (searchproducto.value === '') {
        alert('Por favor, selecciona un producto antes de agregarlo');
        return false;
    }

    if (Cantidad.value === 0 || Cantidad.value  === '' || Cantidad.value < 0) {
        alert('Agrega una cantidad valida.')
        return false;
    }

    const nuevoProducto = {
        id: producto_id.value,
        nombre: searchproducto.value,
        cantidad: Cantidad.value
    };

    if (indexEdit.value !== null) {
        form.Productos[indexEdit.value] = { ...nuevoProducto };
        indexEdit.value = null;

        setTimeout( () => {
            banderaproducto.value = false;
        }, 100);

    } else {
        form.Productos.push(nuevoProducto);        
    }
    
    searchproducto.value = '';
    Cantidad.value = '';
    producto_id.value = '';

    
}
const indexEdit = ref(null);
const EditProduct = (index) => {    
    let producto = form.Productos[index];
    indexEdit.value = index;
    producto_id.value = producto.id;
    Cantidad.value = producto.cantidad;
    searchproducto.value = producto.nombre;
    banderaproducto.value = true;
}

const showSuccess = (msg) => {
    toast.add({ severity: 'success', summary: 'Success', detail: msg, life: 3000 });
};

const showError = (msg) => {
    toast.add({ severity: 'error', summary: 'Error', detail: msg, life: 3000 });
};


</script>

<template>
    <Head title="Salida" />

    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            
            <div class="flex justify-between items-center mb-5">            
                <h3 class="text-2xl font-bold text-gray-900">Salidas</h3>                        
                <PrimaryButton type="button" @click="showmodal = true">
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
                        <InputLabel for="search_nosalida" value="No° de salida"/>
                        <TextInput
                            id="search_nosalida"
                            type="search"
                            class="w-full mt-1"
                            v-model="formsearch.search_nosalida"
                        />
                    </div>
                    <div>
                        <InputLabel for="search_noentrada" value="No° de compra"/>
                        <TextInput
                            id="search_noentrada"
                            type="text"
                            class="min-w-full mt-1"
                            v-model="formsearch.search_noentrada"
                        />
                    </div>
                    <div>
                        <InputLabel for="search_fechasalida" value="Fecha de salida"/>
                        <TextInput
                            id="search_fechasalida"
                            type="date"
                            class="w-full mt-1"
                            v-model="formsearch.search_fechasalida"
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
                    <SecondaryButton type="button" @click="ClearFormSalida">Clear</SecondaryButton>
                    <PrimaryButton type="button" @click="SearchSalidaTable">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                        </svg>
                    </PrimaryButton>
                </div>
            </div>
            <DataTable :value="salidas" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="no_salida" header="No° Salida"></Column>
                <Column field="no_orden" header="No° de compra"></Column>
                <Column field="fecha_salida" header="Fecha de salida"></Column>
                <Column field="area" header="Area"></Column>
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

        <Modal :show="showmodal" @close="closeModal">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t md:p-5">
                <h3 class="text-xl font-semibold text-gray-900">
                    Salida
                </h3>
                <button type="button" @click="closeModal" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <form @submit.prevent="submit">
                <div class="p-4 space-y-4 md:p-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <TextInput                         
                            id="id"
                            type="hidden"
                            v-model="form.id"
                        />
                        <div class="relative">
                            <InputLabel for="searchcompra" value="No° orden de compra"/>
                            <TextInput
                                id="searchcompra"
                                type="search"
                                class="w-full mt-1"                            
                                placeholder="Buscar..."
                                v-model="searchcompra"
                            />                            
                            <SearchResult v-if="searchcompra" :id="'searchcompra'" :data="dataCompra" :label="'id'" :text="'no_orden'" @select="handleSelectionCompra" />
                            <FieldError :message="msgerrors.fk_no_compra" />
                        </div>
                        <div>
                            <InputLabel for="no_salida" value="No° de salida"/>
                            <TextInput
                                id="no_salida"
                                type="text"
                                class="w-full mt-1"                                
                                v-model="form.no_salida"
                            />
                            <FieldError :message="msgerrors.no_salida" />
                        </div>
                        <div>
                            <InputLabel for="fecha_salida" value="Fecha de salida"/>
                            <TextInput
                                id="fecha_salida"
                                type="date"
                                class="w-full mt-1"
                                v-model="form.fecha_salida"
                            />
                            <FieldError :message="msgerrors.fecha_salida" />

                        </div>                    
                        <div class="relative">
                            <InputLabel for="searcharea" value="Area"/>
                            <TextInput
                                id="searcharea"
                                type="search"
                                class="w-full mt-1"
                                placeholder="Buscar..."
                                v-model="searcharea"
                            />
                            <SearchResult v-if="searcharea" :id="'searcharea'" :data="dataArea" :label="'id'" :text="'area'" @select="handleSelectionArea" />
                            <FieldError :message="msgerrors.fk_area" />
                        </div>                    
                        <div>
                            <InputLabel for="personal" value="Quien recibio"/>
                            <TextInput
                                id="personal"
                                type="text"
                                class="w-full mt-1"
                                v-model="form.personal"
                            />
                            <FieldError :message="msgerrors.personal" />
                        </div>                        
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="relative">
                            <InputLabel for="searchproducto" value="Agregar productos"/>
                            <TextInput
                                id="searchproducto"
                                type="search"
                                class="w-full mt-1"                            
                                placeholder="Buscar..."
                                v-model="searchproducto"
                            />                            
                            <SearchResult v-if="searchproducto" :id="'searchproducto'" :data="dataProducto" :label="'id'" :text="'nombre'" @select="handleSelectionProducto" />                            
                        </div>
                        <div class="flex items-end gap-1 justify-between">
                            <div class="flex-1">
                                <InputLabel for="Cantidad" value="Cantidad"/>
                                <TextInput
                                    id="Cantidad"
                                    type="number"
                                    class="w-full mt-1"
                                    v-model="Cantidad"
                                />                                
                            </div>
                            <PrimaryButton type="button" @click="addProduct">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Agregar</span>
                                </div>
                            </PrimaryButton>
                        </div>                        
                        <FieldError :message="msgerrors.Productos" />
                        <FieldError :message="msgerrors.msg" :textcolor="'text-blue-600'" />
                    </div>
                    <div>
                        <h3 class="mb-3 font-bold text-md">Productos agregados</h3>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-y-auto list-none border border-gray-200 rounded-md">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-2">Producto</th>
                                        <th class="px-6 py-2">Cantidad</th>
                                        <th class="px-6 py-2">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index ) in form.Productos" :key="index" class="bg-white border-b border-gray-200">
                                        <td class="px-6 py-2">{{ item.nombre }}</td>
                                        <td class="px-6 py-2">{{ item.cantidad }}</td>
                                        <td class="px-6 py-2">
                                            <div class="flex gap-5">
                                                <button type="button" class="hover:text-red-700" @click="Removeproduct(index)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                                                        <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button type="button" class="hover:text-blue-700" @click="EditProduct(index)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>
                    </div>
                </div>
            
                <!-- Modal footer -->
                <div class="flex items-center justify-end gap-3 p-4 border-t border-gray-200 rounded-b md:p-5">
                    <SecondaryButton type="button" @click="closeModal">                        
                        cancelar
                    </SecondaryButton>
                    <PrimaryButton type="submit" :Show="showspinner" :Disabled="btndisabled"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>guardar</span>
                    </PrimaryButton>
                </div>        
            </form>                 
        </Modal>

        
        
    </AuthenLayout>
</template>