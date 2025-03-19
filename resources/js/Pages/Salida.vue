<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SearchResult from '@/Components/SearchResult.vue';
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
const showmodal = ref(false);

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

const data2 = ref([]);

const size = ref({ label: 'Small', value: 'small' });

const closeModal = () => {
    showmodal.value = false;
}

onMounted(() => {    
    products.value = data;    
});

const form = useForm({ 
    id: '',
    NoOrden: '',
    FechaSalida: '',
    NoSalida: '',      
    Area: '',  
    Quienrecibe: '',
    imgproduct: '',
    Productos: [],
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
    <Head title="Salida" />

    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">
            <h3 class="mb-5 text-2xl font-bold text-gray-900">Salidas</h3>
            
            <div class="flex justify-end mb-5">
                <PrimaryButton type="button" @click="showmodal = true">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Agregar</span>                    
                    </div>
                </PrimaryButton>
            </div>
            <DataTable :value="products" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="Code" header="No° Salida"></Column>
                <Column field="Name" header="No° de compra"></Column>
                <Column field="Name" header="Fecha de salida"></Column>
                <Column field="Name" header="Area"></Column>
                <Column field="Name" header="Personal"></Column>
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

        <Modal :show="showmodal" @close="closeModal">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t md:p-5">
                <h3 class="text-xl font-semibold text-gray-900">
                    Salida
                </h3>
                <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal body -->
            <div class="p-4 space-y-4 md:p-5">
            </div>
            
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b md:p-5">
                
            </div>                         
        </Modal>
        
        <Drawer v-model:visible="visibleRight" header="Salida" position="right" class="!w-[25rem]">
            <form @submit.prevent="submit">
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
                            v-model="form.NoOrden"
                        />
                        <SearchResult :id="'NoOrden'" :data="data2" :label="'Code'" :text="'Name'" :SelectOption="editProduct" />
                    </div>
                    <div>
                        <InputLabel for="NoSalida" value="No° de salida"/>
                        <TextInput
                            id="NoSalida"
                            type="text"
                            class="w-full mt-1"
                            placeholder="Buscar..."
                            v-model="form.NoSalida"
                        />
                        <SearchResult :id="'NoSalida'" :data="data2" :label="'Code'" :text="'Name'" :SelectOption="editProduct" />
                    </div>
                    <div>
                        <InputLabel for="FechaSalida" value="Fecha de salida"/>
                        <TextInput
                            id="FechaSalida"
                            type="date"
                            class="w-full mt-1"
                            v-model="form.FechaSalida"
                        />
                    </div>                    
                    <div class="relative">
                        <InputLabel for="Area" value="Area"/>
                        <TextInput
                            id="Area"
                            type="search"
                            class="w-full mt-1"                            
                            placeholder="Buscar..."
                            v-model="form.Area"
                        />
                        <SearchResult :id="'Area'" :data="data2" :label="'Code'" :text="'Name'" :SelectOption="editProduct" />
                    </div>                    
                    <div>
                        <InputLabel for="Quienrecibe" value="Quien recibio"/>
                        <TextInput
                            id="Quienrecibe"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.Quienrecibe"
                        />
                    </div>
                    <div class="relative">
                        <InputLabel for="Productos" value="Agregar productos"/>
                        <TextInput
                            id="Productos"
                            type="search"
                            class="w-full mt-1"                            
                            placeholder="Buscar..."
                            v-model="form.Productos"
                        />
                        <SearchResult :id="'Productos'" :data="data2" :label="'Code'" :text="'Name'" :SelectOption="editProduct" />
                    </div>
                    <div class="">
                        <h3 class="mb-1 font-bold text-gray-600 text-md">Productos agregados</h3>
                        <ul class="w-full py-1 list-none border rounded-sm shadow-sm max-h-[15rem] overflow-y-auto">                            
                            <li v-for="item in 2" class="flex items-center justify-between p-1 px-2 border-b hover:bg-gray-100">
                                {{  item }}
                                <button type="button" class="hover:text-red-700" @click="editProduct">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-gary-600 size-5">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
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
