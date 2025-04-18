<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SearchResult from '@/Components/SearchResult.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref, onMounted, watch} from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import axios from 'axios';

const products = ref([]);

const dataProducto = ref([]);
const searchproducto = ref(null);
const banderaproducto = ref(false);
const Cantidad = ref('');
const producto_id = ref(null); 

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
    Cantidad: '',
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


const addProduct = () => {
    if (Cantidad.value === 0 || Cantidad.value  === '' || Cantidad.value < 0) {
        alert('Agrega una cantidad valida.')
        return false;
    }
    form.Productos.push({ id: producto_id.value, nombre: searchproducto.value, cantidad: Cantidad.value });
    searchproducto.value = '';
    Cantidad.value = '';
    producto_id.value = '';
    console.log(form.Productos);
}

let timeoutproduct = null;
watch(searchproducto, (newvalue) => {
    if (banderaproducto.value) return false;
    
    clearTimeout(timeoutproduct);
    timeoutproduct = setTimeout(() => {
        SearchProductos(newvalue)
    }, 500);
});

const Removeproduct = (index) => {
    console.log(index);
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
                            <SearchResult :id="'searchproducto'" :data="dataProducto" :label="'id'" :text="'nombre'" @select="handleSelectionProducto" />
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
                    </div>
                    <div>
                        <h3 class="mb-3 font-bold text-md">Productos agregados</h3>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-y-auto list-none border border-gray-200 rounded-md">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <th class="px-6 py-2">Producto</th>
                                        <th class="px-6 py-2">Cantidad</th>
                                        <th class="px-6 py-2">Accion</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index ) in form.Productos" :key="index" class="bg-white border-b border-gray-200">
                                            <td class="px-6 py-2">{{ item.nombre }}</td>
                                            <td class="px-6 py-2">{{ item.cantidad }}</td>
                                            <td class="px-6 py-2">
                                                <button type="button" class="hover:text-red-700" @click="Removeproduct(index)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                                                        <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                <!-- </li> -->
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
