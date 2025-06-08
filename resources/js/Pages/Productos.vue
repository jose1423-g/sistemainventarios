<script setup>
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import SearchResult from '@/Components/SearchResult.vue';
import TextArea from '@/Components/TextArea.vue'
import Can from '@/Components/Can.vue'
import FieldError from '@/Components/FieldError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Drawer from 'primevue/drawer';
import axios from 'axios';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
const toast = useToast();

const props = defineProps({
    Productos: {
        type: Array,
        default: []
    }    
});

const visibleRight = ref(false);
const showspinner = ref(true);
const btndisabled = ref(false);
const msgerrors = ref([]);
const url_img = ref(null);

const searchproducto = ref('');
const searchpartida = ref('');
const searchentrada = ref('');

const fileinput = ref(null);


const size = ref({ label: 'Small', value: 'small' });

const form = useForm({ 
    id: '',
    fk_entrada: '',
    nombre: '',
    fk_partida: '',
    descripcion: '',
    stock: '',
    unidad: '',
    precio: '',
    img: '',
});

const formsearch = useForm({
    search_product: '',
    search_nopartida: '',
});

const productos = ref(props.Productos);

watch(() => props.Productos, (newVal) => {
    productos.value = [...newVal];
});

const SearchProductTable = async () => {
    try {
        let resp = await axios.post(route('search.product.table'), formsearch);
        productos.value = resp.data;
    } catch (error) {
        showError(error.response.data.msg)
    }
}

const ClearFormProduct = async () => {
    formsearch.reset();
    productos.value = [...props.Productos];        
}


const submit = async () => {    
    
    showspinner.value = false;
    btndisabled.value = true; 
    
    const formData = new FormData()

    formData.append('id', form.id);
    formData.append('fk_entrada', form.fk_entrada);
    formData.append('nombre', form.nombre);
    formData.append('fk_partida', form.fk_partida);
    formData.append('descripcion', form.descripcion);
    formData.append('stock', form.stock);
    formData.append('unidad', form.unidad);
    formData.append('precio', form.precio);
    formData.append('img', form.img);

    try {
        let resp = await axios.post(route('store.producto'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        if (resp.data.result == 1) {
            visibleRight.value = false;
            fileinput.value.Clearfilename();
            searchentrada.value = '';
            searchpartida.value = '';
            searchproducto.value = '';
            showSuccess(resp.data.msg)
            showspinner.value = true;
            btndisabled.value = false;
            form.reset();
            router.reload({ only: ['Productos'] });
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

const showInfo = () => {
    toast.add({ severity: 'info', summary: 'Info Message', detail: '🔄 Cargando información para edición...', life: 3000});
};

const Edit = async (data) => {
    try {
        showInfo();
        let resp = await axios.get(route('edit.producto', data.id));
        if (resp.data.result == 0) {
            showError(resp.data.msg);
        } else {
            visibleRight.value = true;        
            form.id = resp.data.id;         
            form.fk_entrada = resp.data.fk_entrada ?? ''; // solo valida los datos null y undefined
            form.nombre = resp.data.nombre;            
            form.fk_partida = resp.data.fk_partida ?? '';
            form.descripcion = resp.data.descripcion;
            form.stock = resp.data.stock;
            form.unidad = resp.data.unidad;
            form.precio = resp.data.precio;
            form.img = resp.data.img;
            url_img.value = resp.data.url_img;

            searchentrada.value = resp.data.no_orden;
            searchpartida.value = resp.data.no_partida;
            searchproducto.value = resp.data.nombre;
        }
    
    } catch (error) {
        showError(error.response.data.msg);
    }
    
}

const Delete  = async (data) => {
    if (confirm('¿Estas seguro de eliminar este registro?')) {    
        let resp = await axios.delete(route('delete.producto', data.id));
        if (resp.data.result == 1) {        
            showSuccess(resp.data.msg)
            router.reload({ only: ['Productos'] });
        } else {
            showError(resp.data.msg);            
        }
    } else {
        return false;
    }
}


const handleSelectionEntrada = (id) => {
    if (!id == '') {
        form.fk_entrada = id;     
    }
};

const handleSelectionProducto = (id, text) => {
    if (id === '') {
        form.id = ''
        form.nombre = searchproducto.value;
    } else {
        form.id = id; 
        form.nombre = text;
    }
};

const handleSelectionPartida = (id) => {     
    if (!id == '') {
        form.fk_partida = id;
    }
};

const ClearForm = () => {
    form.reset();
    url_img.value = null;
    searchentrada.value = '';
    searchpartida.value = '';
    searchproducto.value = '';
    msgerrors.value  = [];
    visibleRight.value = true;
}

</script>

<template>
    <Head title="Productos" />


    <AuthenLayout>

        <div class="p-5 bg-white border border-gray-200 rounded-sm shadow-md">                    
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-2xl font-bold text-gray-900">Productos</h3>
                <PrimaryButton type="button" @click="ClearForm">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 size-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>Agregar</span>                    
                    </div>
                </PrimaryButton>
            </div>
            <!-- Buscador -->
            <div>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-3">
                    <div>
                        <InputLabel for="search_product" value="Producto"/>
                        <TextInput
                            id="search_product"
                            type="search"
                            class="w-full mt-1"
                            v-model="formsearch.search_product"
                        />
                    </div>
                    <div>
                        <InputLabel for="search_nopartida" value="No° de partida"/>
                        <TextInput
                            id="search_nopartida"
                            type="search"
                            class="w-full mt-1"
                            v-model="formsearch.search_nopartida"
                        />
                    </div>
                </div>                
                <div class="flex justify-end space-x-3 mb-5">
                    <SecondaryButton type="button" @click="ClearFormProduct">Clear</SecondaryButton>
                    <PrimaryButton type="button" @click="SearchProductTable">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                        </svg>
                    </PrimaryButton>
                </div>                
            </div>

            <DataTable :value="productos" :size="size.value" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="nombre" header="Producto"></Column>
                <Column field="no_partida" header="No° Partida"></Column>
                <Column field="no_orden" header="No° orden de compra"></Column>
                <Column field="fecha_compra" header="Fecha de compra"></Column>
                <Column field="fecha_entrada" header="Fecha de entrada"></Column>
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
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="grid grid-cols-1 gap-4">
                    <TextInput                         
                        id="id"
                        type="hidden"
                        v-model="form.id"
                    />
                    <div class="relative">
                        <InputLabel for="searchentrada" value="No° orden de compra"/>
                        <SearchResult :url="'search.entradas'" :id="'searchentrada'" v-model="searchentrada" :label="'id'" :text="'no_orden'" @select="handleSelectionEntrada" />
                        <FieldError :message="msgerrors.fk_entrada" />
                    </div>
                    <TextInput
                        id="fk_entrada"
                        type="hidden"
                        class="w-full mt-1"
                        v-model="form.fk_entrada"
                    />
                    <div class="relative">
                        <InputLabel for="searchproducto" value="Producto"/>
                        <SearchResult :url="'search.productos'" :id="'searchproducto'" v-model="searchproducto" :label="'id'" :text="'nombre'" @select="handleSelectionProducto" />
                        <FieldError :message="msgerrors.nombre" />
                    </div>
                    <div>
                        <InputLabel for="descripcion" value="Descripción"/>
                        <TextArea                         
                            id="descripcion"
                            class="w-full mt-1"
                            v-model="form.descripcion"
                        />
                        <FieldError :message="msgerrors.descripcion" />
                    </div>
                    <div>
                        <InputLabel for="Totalarticulos" value="Total de articulos"/>
                        <TextInput
                            id="Totalarticulos"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.stock"
                        />
                        <FieldError :message="msgerrors.stock" />
                    </div>
                    <div>
                        <InputLabel for="unidad" value="Unidad de medida"/>
                        <TextInput
                            id="unidad"
                            type="text"
                            class="w-full mt-1"
                            v-model="form.unidad"
                        />
                        <FieldError :message="msgerrors.unidad" />
                    </div>
                    <div>
                        <InputLabel for="precio" value="Precio"/>
                        <TextInput
                            id="precio"
                            type="number"
                            class="w-full mt-1"
                            v-model="form.precio"
                        />
                        <FieldError :message="msgerrors.precio" />
                    </div>
                    <div>
                        <InputLabel for="img" value="Imagen"/>
                        <InputFile
                            ref="fileinput" 
                            :id="'img'" 
                            :typefiles="['jpeg', 'png', 'webp']"
                            class="block w-full mt-1" 
                            v-model="form.img"
                        />
                        <FieldError :message="msgerrors.img" />
                        <p v-if="form.img" class="text-blue-700 truncate mt-2">
                            <a :href="url_img" target="_blank" class="">{{ url_img }}</a>
                        </p>
                    </div>

                    <div class="relative">
                        <InputLabel for="searchpartida" value="No° partida"/>
                        <SearchResult :url="'search.partidas'" :id="'searchpartida'" v-model="searchpartida" :label="'id'" :text="'no_partida'" @select="handleSelectionPartida" />
                        <FieldError :message="msgerrors.fk_partida" />
                    </div>
                    <TextInput
                        id="fk_partida"
                        type="hidden"
                        v-model="form.fk_partida"
                    />                
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
