<template>
	<div class="container mt-4">
	  	<!-- Mostrar spinner de carga mientras se obtienen los datos -->
	  	<div v-if="loading" class="text-center mt-4">
			<div class="spinner-border" role="status">
		  		<span class="visually-hidden">Cargando...</span>
			</div>
	  	</div>

	  	<div v-else>
				<!-- Barra de búsqueda -->
				<div class="mb-4">
			  		<input
					v-model="searchQuery"
					type="text"
					class="form-control mb-3"
					placeholder="Buscar productos..."
			  	/>
			  	<!-- Filtro por categoría -->
			  	<select v-model="selectedCategory" class="form-select mb-3">
					<option value="">Todas las categorías</option>
					<option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
			  	</select>
			</div>

			<!-- Mostrar mensaje si no hay productos -->
			<div v-if="filteredProducts.length === 0" class="alert alert-info">
			  	No se encontraron productos.
			</div>

			<!-- Mostrar productos -->
			<div v-if="filteredProducts.length > 0">
			  	<div
					class="bg-white p-4 border rounded mb-4"
					v-for="(productsByCategory, category) in groupProductsByCategory"
					:key="category"
			  	>
					<h4 class="mb-3">{{ category }}</h4>
						<div class="row">
				  			<div class="col-md-4 mb-4" v-for="product in productsByCategory" :key="product.id">
								<div class="card h-100 shadow-sm">
					  				<img :src="product.file.route" class="card-img-top" :alt="product.title">
					  				<div class="card-body">
										<h5 class="card-title">{{ product.capital_letters_name }}</h5>
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

	  	<!-- Modal de un producto -->
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
	  		// Asegúrate de que `selectedCategory` esté correctamente establecido
	  		if (filteredProducts.value.length === 0 && categories.value.length > 0) {
				selectedCategory.value = '';
	  		}
		} catch (error) {
		  	console.error('Error al cargar los productos:', error);
		} finally {
		  	loading.value = false;
		}
  	};

  	// Filtrar categorías únicas
  	const categories = computed(() => {
		const allCategories = products.value.map(product => product.category.capital_letter);
		const uniqueCategories = [...new Set(allCategories)];
		return uniqueCategories;
  	});

  	// Filtrar productos basado en búsqueda y categoría
  	const filteredProducts = computed(() => {
		const filteredByCategory = selectedCategory.value
	  								? products.value.filter(product => product.category.capital_letter === selectedCategory.value)
	  								: products.value;
		const result = filteredByCategory.filter(product => product.capital_letters_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
		return result;
  	});

  	// Agrupar productos por categoría para visualización
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

  	// Manejar clic en el botón
  	const handleButtonClick = async (product) => {
		selectedProduct.value = product;
		await openModal('product_modal');
  	};

  	// Monitorear cambios en `filteredProducts` para actualizar `selectedCategory`
  	watch(filteredProducts, (newFilteredProducts) => {
		if (newFilteredProducts.length === 0 && selectedCategory.value !== '') {
	  		selectedCategory.value = ''; // Cambia a "Todas las categorías" si no hay productos
		}
  	});

  	onMounted(() => {
		reloadData();
  	});
</script>
