<template>
	<div class="container mt-4">

	  	<div v-if="loading" class="text-center mt-4">
			<div class="spinner-border" role="status">
		  		<span class="visually-hidden">Cargando...</span>
			</div>
	  	</div>

	  	<div v-else>
			<div class="row mb-4">
		  		<div class="col-md-8 mb-3">
					<input
			  			v-model="searchQuery"
			  			type="text"
			  			class="form-control"
			  			placeholder="Buscar productos..."
					/>
		  		</div>
		  		<div class="col-md-4 mb-3">
					<select v-model="selectedCategory" class="form-select">
			  			<option value="">Todas las categorías</option>
			  			<option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
					</select>
		  		</div>
			</div>

			<div v-if="filteredProducts.length === 0" class="alert alert-info">
		  		No se encontraron productos.
			</div>

			<div v-if="filteredProducts.length > 0">
		  		<div
					class="category-section mb-4"
					v-for="(productsByCategory, category) in groupProductsByCategory"
					:key="category"
		  		>
					<h4 class="mb-3 category-title">{{ category }}</h4>
					<div class="row">
			  			<div class="col-md-4 mb-4" v-for="product in productsByCategory" :key="product.id">
							<div class="card product-card h-100 shadow-sm">
				  				<img :src="product.file.route" class="card-img-top product-img" :alt="product.title">
				  				<div class="card-body">
									<h5 class="card-title">{{ product.capital_letters_name }}</h5>
									<p class="card-text">{{ product.short_description }}</p>
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
	  	</div>

	  	<div v-if="load_modal">
			<product-modal-buyer :product="selectedProduct" :closeModal="closeModal" @product-updated="reloadData"/>
	  	</div>
	</div>
</template>
<script setup>
  	import { ref, computed, onMounted, watch } from 'vue';
  	import ProductModalBuyer from './ProductModalBuyer.vue';
  	import HandlerModal from '@/helpers/HandlerModal';

  	const searchQuery = ref('');
  	const selectedCategory = ref('');
  	const loading = ref(true);
  	const products = ref([]);
  	const selectedProduct = ref({});
  	const { openModal, closeModal, load_modal } = HandlerModal();

  	const reloadData = async () => {
		loading.value = true;
		try {
	  		const response = await axios.get('/products/index');
			products.value = response.data;
	  		if (filteredProducts.value.length === 0 && categories.value.length > 0) {
				selectedCategory.value = '';
	  		}
		} catch (error) {
	  		console.error('Error al cargar los productos:', error);
		} finally {
	  		loading.value = false;
		}
  	};

  	const categories = computed(() => {
		const allCategories = products.value.map(product => product.category.capital_letter);
		const uniqueCategories = [...new Set(allCategories)];
		return uniqueCategories;
  	});

  	const filteredProducts = computed(() => {
		const filteredByCategory = selectedCategory.value
	  	? products.value.filter(product => product.category.capital_letter === selectedCategory.value)
	  	: products.value;
		const result = filteredByCategory.filter(product => product.capital_letters_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
		return result;
  	});

  	const groupProductsByCategory = computed(() => {
		return filteredProducts.value.reduce((groupedProducts, product) => {
	  		const category = product.category.capital_letter;
	  		if (!groupedProducts[category]) {
				groupedProducts[category] = [];
	  		}
	  		groupedProducts[category].push(product);
	  		return groupedProducts;
		}, {});
  	});

  	const handleButtonClick = async (product) => {
		selectedProduct.value = product;
		await openModal('product_modal');
  	};

  	watch(filteredProducts, (newFilteredProducts) => {
		if (newFilteredProducts.length === 0 && selectedCategory.value !== '') {
	  		selectedCategory.value = '';
		}
  	});

  	onMounted(() => {
		reloadData();
  	});
</script>
