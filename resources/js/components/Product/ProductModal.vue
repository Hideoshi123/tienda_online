<template>
	<div class="modal fade" id="uni_product_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="product_modal_label" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">

		  		<div class="modal-header">
					<h5 class="modal-title" id="product_modal_label">{{ is_create ? 'Crear Producto' : product.capital_letters_name }}</h5>
					<button type="button" class="btn-close" @click="handleClose" aria-label="Close"></button>
		  		</div>

		  		<div class="modal-body d-flex flex-column flex-md-row align-items-center">
					<form @submit.prevent="saveProduct">
			  			<div class="modal-body">
							<section class="row">

				  				<backend-error :errors="back_errors" />

				  				<div class="col-12 d-flex justify-content-center mt-1">
									<img :src="imagePreview" alt="Imagen Producto" class="img-thumbnail" width="170" height="170">
				  				</div>

				  				<div class="col-12 mt-1">
									<label for="file" class="form-label">Imagen</label>
									<input type="file" :class="`form-control ${back_errors.file ? 'is-invalid' : ''}`" id="file" accept="image/*" @change="previewImage">
									<span class="invalid-feedback" v-if="back_errors.file">{{ back_errors.file }}</span>
				 	 			</div>

				  				<div class="col-12 mt-2">
									<label for="name">Nombre</label>
									<input type="text" id="name" v-model="product.name" :class="`form-control ${back_errors.name ? 'is-invalid' : ''}`">
									<span class="invalid-feedback">{{ back_errors.name }}</span>
				  				</div>

				  				<div class="col-12 mt-2">
									<label for="stock">Cantidad</label>
									<input type="number" id="stock" v-model="product.stock" :class="`form-control ${back_errors.stock ? 'is-invalid' : ''}`">
									<span class="invalid-feedback">{{ back_errors.stock }}</span>
				  				</div>

				  				<div class="col-12 mt-2">
									<label for="description">Descripción</label>
									<textarea v-model="product.description" :class="`form-control ${back_errors.description ? 'is-invalid' : ''}`" id="description" rows="3"></textarea>
									<span class="invalid-feedback">{{ back_errors.description }}</span>
				  				</div>

				  				<div class="col-12 mt-2">
									<label for="price">Precio</label>
									<input type="text" id="price" v-model="product.price" :class="`form-control ${back_errors.price ? 'is-invalid' : ''}`">
									<span class="invalid-feedback">{{ back_errors.price }}</span>
				  				</div>

				  				<div class="col-12 mt-2" v-if="loadCategory">
									<label for="category">Categoría</label>
									<v-select id="category" :options="categoriesData" v-model="category" :reduce="category => category.id" label="name" placeholder="Selecciona categoría" :clearable="false" :class="`form-control ${back_errors.category_id ? 'is-invalid' : ''}`">
									</v-select>
									<span class="invalid-feedback">{{ back_errors.category_id }}</span>
				  				</div>
							</section>
			  			</div>

			  			<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Guardar</button>
			  			</div>
					</form>
		  		</div>
			</div>
	  	</div>
	</div>
</template>
<script setup>
  	import { ref, onMounted } from 'vue'
  	import axios from 'axios'
  	import { handlerErrors, successMessage } from '@/helpers/Alerts.js'

  	const props = defineProps({
		product_data: {
	  		type: Object
		},
  	});

  	const is_create = props.product_data ? ref(false) : ref(true)
  	const product = ref(is_create.value ? {} : { ...props.product_data })
  	const back_errors = ref({})
  	const categoriesData = ref([])
  	const loadCategory = ref(false)
  	const category = ref(null)
  	const imagePreview = ref('/storage/images/products/default.jpg')
  	const file = ref(null)
  	const emit = defineEmits(['reload_state', 'close_modal'])

  	const handleClose = () => {
		resetData();
		emit('close_modal');
  	};

  	const saveProduct = async () => {
		try {
		  	const formData = new FormData();
		  	formData.append('name', product.value.name);
		  	formData.append('stock', product.value.stock);
		  	formData.append('description', product.value.description || 'Sin descripcion');
		  	formData.append('price', product.value.price);
		  	formData.append('category_id', category.value);
		  	if (file.value) formData.append('file', file.value);

		  	if (is_create.value) {
				await axios.post('/products/store', formData);
		  	} else {
				await axios.post(`/products/update/${product.value.id}`, formData);
		  	}

		  	successMessage({ is_delete: false, reload: false }).then(() => {
				emit('reload_state');
				handleClose();
		  	});
		} catch (error) {
		  	back_errors.value = await handlerErrors(error);
		}
  	};

  	const getCategories = async () => {
		try {
		  	const { data } = await axios.get('/categories/get-all');
		  	categoriesData.value = data.categories;
		  	loadCategory.value = true;
		} catch (error) {
		  	await handlerErrors(error);
		}
  	};

  	const previewImage = (event) => {
		file.value = event.target.files[0];
		imagePreview.value = URL.createObjectURL(file.value);
  	};

  	const resetData = () => {
		product.value = {};
		category.value = null;
		imagePreview.value = '/storage/images/products/default.jpg';
		file.value = null;
  	};

  	onMounted(() => {
		getCategories();
		if (props.product_data) {
		  console.log(props.product_data)
		  imagePreview.value = props.product_data.file.route;
		  category.value = props.product_data.category_id;
		}
  	});
</script>
