<template>
    <div class="container mt-4">
        <div v-if="loading" class="text-center mt-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <div v-else>

			<div class="mb-3 category-title d-flex justify-content-around">
				<h1>Categoria: {{ category.capital_letter }}</h1>
				<button @click="redirectToHome" class="btn btn-warning">Ver todas las categorias</button>
			</div>

            <div class="row mb-4">
                <div class="col-md-8 mb-3">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="form-control"
                        placeholder="Buscar productos..."
                    />
                </div>
            </div>

            <div v-if="filteredProducts.length === 0" class="alert alert-info">
                No se encontraron productos.
            </div>

            <div v-if="filteredProducts.length > 0">
                <div class="row">
                    <div class="col-md-4 mb-4" v-for="product in filteredProducts" :key="product.id">
                        <div class="card product-card h-100 shadow-sm">
                            <img :src="product.file.route" class="card-img-top product-img" :alt="product.title">
                            <div class="card-body d-flex align-items-center flex-column">
                                <h5 class="card-title">{{ product.capital_letters_name }}</h5>
                                <p class="card-text">{{ product.format_description }}</p>
                                <p class="card-price">{{ product.price }}$</p>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-outline-success" @click="handleButtonClick(product)">Ver detalles</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="load_modal">
            <product-modal-buyer :product="selectedProduct" :closeModal="closeModal" @product-updated="reloadData"/>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ProductModalBuyer from './ProductModalBuyer.vue';
import HandlerModal from '@/helpers/HandlerModal';

const props = defineProps({
    categoryId: Number
});

const searchQuery = ref('');
const loading = ref(true);
const products = ref([]);
const category = ref({});
const selectedProduct = ref({});
const { openModal, closeModal, load_modal } = HandlerModal();

const reloadData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/products/get-all-by-category/${props.categoryId}`);
        category.value = response.data.category;
        products.value = response.data.products;
    } catch (error) {
        console.error('Error al cargar los productos:', error);
    } finally {
        loading.value = false;
    }
};

const filteredProducts = computed(() => {
    return products.value.filter(product =>
        product.capital_letters_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const handleButtonClick = async (product) => {
    selectedProduct.value = product;
    await openModal('product_modal');
};

const redirectToHome = () => {
    window.location.href = '/';
};

onMounted(() => {
    reloadData();
});
</script>
