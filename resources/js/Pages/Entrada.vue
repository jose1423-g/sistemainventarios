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

const searchText = ref();

const data = [
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': 'f230fh0g3', 'Name': 'Bamboo Watch',	'Category': 'Accessories', 'Quantity' : '24'},
        {'Code': '123456', 'Name': 'Juan',	'Category': 'Accessories', 'Quantity' : '25'},
];

const size = ref({ label: 'Small', value: 'small' });

onMounted(() => {    
    products.value = data;    
});

const form = useForm({ 
    id: '',
    NoOrden: '',
    Proveedor: '',
    FechaCompra: '',
    FechaEntrada: '', 
    AreaSolicitante: '',
    Norequisicion: '',
    CantidadPiezas: '',
    PrecioUnitario: '',
    Iva: '',
    Total: '',
});

const submit = async () => {
    showspinner.value = false;
    btndisabled.value = true;
    // let resp = await axios.post(route(''), form);
    // console.log(resp);
}

const editProduct  = (holis) => {
    console.log(holis);
}

/* funcion para realizar busquedas */
function Search () {    
    if (searchText.value.trim()) {
        console.log(searchText)
        products.value = data2;
    } else {
        console.log('no tiene nada')
        products.value = data;
    }
    
}

</script>

<template>
    <Head title="Partida presupuestal" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Entradas</h3>
            
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
            <DataTable :value="products" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="Code" header="No° Entrada"></Column>
                <Column field="Name" header="Fecha de compra"></Column>
                <Column field="Name" header="Fecha de entrada"></Column>
                <Column field="Name" header="Area solicitante"></Column>
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
        
        <Drawer v-model:visible="visibleRight" header="Entrada de producto" position="right" class="!w-[25rem]">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />                    
                    <div>
                        <InputLabel for="NoOrden" value="No° orden de compra"/>
                        <TextInput
                            id="NoOrden"
                            type="text"
                            class="w-full mt-1"                            
                            v-model="form.NoOrden"
                        />
                    </div>
                    <div>
                        <InputLabel for="Proveedor" value="Proveedor"/>
                        <TextInput
                            id="Proveedor"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Proveedor"
                        />
                    </div>
                    <div>
                        <InputLabel for="FechaCompra" value="Fecha de compra"/>
                        <TextInput
                            id="FechaCompra"
                            type="date"
                            class="w-full mt-1"
                            v-model="form.FechaCompra"
                        />
                    </div>
                    <div>
                        <InputLabel for="FechaEntrada" value="Fecha de entrada"/>
                        <TextInput
                            id="FechaEntrada"
                            type="date"
                            class="w-full mt-1"
                            v-model="form.FechaEntrada"
                        />
                    </div>
                    <div>
                        <InputLabel for="AreaSolicitante" value="Area solicitante"/>
                        <TextInput
                            id="AreaSolicitante"
                            type="search"
                            class="w-full mt-1"
                            placeholder="Buscar..."
                            v-model="form.AreaSolicitante"
                        />
                        <SearchResult :data="data = []" :label="'Code'" :text="'Name'" :SelectOption="editProduct" />
                    </div>  
                    <div>
                        <InputLabel for="Norequisicion" value="No° requisición"/>
                        <TextInput
                            id="Norequisicion"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Norequisicion"
                        />
                    </div>
                    <div>
                        <InputLabel for="CantidadPiezas" value="Cantidad/Piezas"/>
                        <TextInput
                            id="CantidadPiezas"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.CantidadPiezas"
                        />
                    </div>
                    <div>
                        <InputLabel for="PrecioUnitario" value="Precio Unitario"/>
                        <TextInput
                            id="PrecioUnitario"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.PrecioUnitario"
                        />
                    </div>                    
                    <div>
                        <InputLabel for="Iva" value="IVA"/>
                        <TextInput
                            id="Iva"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Iva"
                        />
                    </div>                    
                    <div>
                        <InputLabel for="Total" value="Total"/>
                        <TextInput
                            id="Total"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Total"
                        />
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
