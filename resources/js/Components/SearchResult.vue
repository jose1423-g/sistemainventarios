<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';

const model = defineModel({
    type: [String, Number, null],
    required: false,    
});

const bandera = ref(false);
const searchContainer = ref(null);
const showDropdown = ref(true);
const searching = ref(false);
const dataError = ref('');

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    url: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        required: false,
    },
    text: {
        type: String,
        required: false,
    },
})

const dataProducto = ref([]);

const emit = defineEmits(['select']); // Define el evento

const handleSelect = (id, text, item) => {
    model.value = text;
    emit('select', id, text, item); // Emitir el valor seleccionado
    dataProducto.value = [];
    dataError.value = '';
};

function handleFocus () {
    showDropdown.value = true;
}

const Search = async (newproduct) => {
    if (newproduct) {        
        searching.value = true; 
        dataError.value = '';
        try {
            let resp = await axios.get(route(props.url, newproduct));                        
            dataProducto.value = resp.data;
        } catch (error) {            
            dataProducto.value = [];
            dataError.value = error.response.data.name;
        } finally {
            searching.value = false; // Finaliza búsqueda
        }
    } else {
        dataProducto.value = [];
        bandera.value = false;
        dataError.value = '';
    }    
}

let timeoutproduct = null;
watch(model, (newvalue) => {
    if (bandera.value) return false;    
    clearTimeout(timeoutproduct);
    timeoutproduct = setTimeout(() => {
        Search(newvalue)
    }, 500);
});

const handleClickOutside = (event) => {
  if (searchContainer.value && !searchContainer.value.contains(event.target)) {
    showDropdown.value = false
    dataProducto.value = [];
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
});


</script>
<template>
    <div class="relative" ref="searchContainer">
        <input type="search" :id="id" placeholder="Buscar..." autocomplete="off" class="p-1 text-gray-700 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full" v-model="model" @focus="handleFocus">
        <ul :id="id" v-if="(dataProducto.length || searching || dataError) && showDropdown" class="absolute z-30 w-full py-1 list-none bg-white border rounded-sm shadow-sm max-h-[10rem] overflow-y-auto mt-[.1rem]">
            <li v-if="searching" class="px-2 py-1 text-gray-500 italic cursor-default">
                Buscando...
            </li>    
            <li v-if="dataError" class="px-2 py-1 text-gray-500 italic cursor-default" @click="handleSelect('', model)">
                {{ dataError }}
            </li>                     
            <li class="px-2 py-1 cursor-pointer hover:bg-gray-300" v-for="item in dataProducto" @click="handleSelect(item[label], item[text], item)">{{ item[text] }}</li>
        </ul>
    </div>
    

    
</template>