<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthenLayout from '@/Layouts/AuthenLayout.vue'
import { Head } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import axios from 'axios';
import {ref} from 'vue';

const visible = ref(false);
const showmodal = ref(false);
const btndisabled = ref(false);
const showspinner = ref(true);
const currentEntradaId = ref(null);
const showPdfModal = ref(false);

// Método para cerrar modal de PDF
const closePdfModal = () => {
    showPdfModal.value = false;
};

// Método para mostrar el PDF
// En Dashboard.vue
const viewPDF = async (data) => {
    showPdfModal.value = true;
    // console.log(data.id)
    // console.log("Visualizando PDF para no_orden:", no_orden); // Para depuración
    let resp = await axios.get(route('ver.pdf', data.id));
    console.log(resp)
    // currentEntradaId.value = no_orden;
    
};

const closeModal = () => {    
    showmodal.value = false;
}

const props = defineProps({
    entradas: {
        type: Number,
    }, 
    salidas: {
        type: Number,
    },
    datosEntradas: {
        type: Array,
    },
    datosSalidas: {
        type: Array,
    }
});

const downloadPDF = () => {
    const url = currentEntradaId.value 
        ? route('descargar.pdf', currentEntradaId.value) 
        : route('descargar.pdf');
    window.open(url, '_blank');
};

</script>

<template>
    <Head title="Partida Presupuestal" />

    <AuthenLayout>
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-semibold text-gray-800">Panel de Control</h1>
            
            <!-- Cards para Entradas y Salidas -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
                <!-- Card de Entradas -->
                <div class="overflow-hidden bg-white border-l-4 border-blue-500 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Entradas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ entradas }}</p>
                                <p class="text-sm text-gray-500">Productos ingresados al inventario</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card de Salidas -->
                <div class="overflow-hidden bg-white border-l-4 border-red-500 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Salidas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ salidas }}</p>
                                <p class="text-sm text-gray-500">Productos enviados a áreas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
                <!-- Card de Consulta de Entradas -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Consultar Entradas</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-gray-600">Revise el historial de entradas de productos al inventario.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Fecha</th>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Numero de orden</th>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Visualizar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                <tr v-for="entrada in datosEntradas" :key="entrada.no_orden" class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ new Date(entrada.fecha_entrada).toLocaleDateString() }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ entrada.no_orden }}</td>
                                    <td class="px-4 py-2 text-sm text-center text-gray-500">
                                        <button @click="viewPDF(entrada)" class="inline-flex items-center justify-center hover:text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Card de Consulta de Salidas -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Consultar Salidas</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-gray-600">Revise el historial de salidas de productos del inventario.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Fecha</th>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Numero de Salida</th>
                                        <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Visualizar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="salida in datosSalidas" :key="salida.no_salida" class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ new Date(salida.fecha_salida).toLocaleDateString() }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ salida.no_salida }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">
                                            <button @click="viewPDF(entrada.no_orden)" class="inline-flex items-center justify-center hover:text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h1 class="mb-4 text-xl font-semibold">Generar PDF</h1>
                <button @click="generarPDF" class="px-4 py-2 text-white transition bg-blue-500 rounded hover:bg-blue-600">
                    Descargar PDF
                </button>
            </div>
        </div>
    <!-- Modal para visualizar PDF -->
    <Modal :show="showPdfModal" @close="closePdfModal">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            <!-- Vista previa de Entrada #{{ currentEntradaId.value }} -->
        </h2>
        
        <div class="mt-4" style="height: 70vh;">
            <!-- Añadimos un timestamp para evitar caché -->
            <!-- <iframe 
                :src="`${route('ver.pdf', currentEntradaId.value)}?t=${Date.now()}`" 
                style="width: 100%; height: 100%;" 
                frameborder="0">
            </iframe> -->
        </div>
        
        <div class="flex justify-end mt-6">
            <SecondaryButton @click="closePdfModal" class="mr-2">
                Cerrar
            </SecondaryButton>
            <PrimaryButton @click="downloadPDF" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </svg>
                Descargar PDF
            </PrimaryButton>
        </div>
    </div>
</Modal>
    </AuthenLayout>
</template>