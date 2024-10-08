<template>
	<div class="modal fade" id="product_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="product_modal_label" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
		  		<div class="modal-header">
					<h5 class="modal-title" id="product_modal_label">{{ product.capital_letters_name }}</h5>
					<button type="button" class="btn-close" @click="handleClose" aria-label="Close"></button>
		  		</div>
		  		<div class="modal-body d-flex flex-column flex-md-row align-items-center">
					<img :src="product.file.route" class="img-fluid mb-3 mb-md-0 me-md-4" style="max-width: 300px; height: auto; object-fit: cover;" alt="Product Image">
					<div class="w-100">
			  			<p class="mb-3">{{ product.capital_letter_description }}</p>
			  			<p class="mb-3"><strong>Cantidad:</strong> {{ product.stock }}</p>
			  			<p class="mb-3"><strong>Precio:</strong> {{ product.price }} $</p>
			  			<div v-if="isLoggedIn">
							<form @submit.prevent="handleSubmit">
				  				<BackendError :errors="back_errors" />
				  				<input id="cart_id" type="hidden" v-model="formValues.cart_id" />
				  				<input id="product_id" type="hidden" v-model="formValues.product_id" />
				  				<input id="quantity" type="hidden" v-model="formValues.quantity" value="1" />
				  				<button type="submit" class="btn btn-primary w-100">
									Añadir al carrito
									<i class="fas fa-shopping-cart ms-2"></i>
				  				</button>
							</form>
			  			</div>
			  			<div v-else>
							<p class="text-danger">Debes estar logueado para añadir productos al carrito.</p>
			  			</div>
					</div>
		  		</div>
			</div>
	  	</div>
	</div>
</template>
<script setup>
  	import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  	import { handlerErrors, successMessage } from '@/helpers/Alerts.js';
  	import BackendError from '../Components/BackendError.vue';
  	import { emitter } from '@/helpers/eventBus';

  	const props = defineProps({
		product: {
	  		type: Object,
	  		required: true
		},
		closeModal: {
	  		type: Function,
	  		required: true
		}
  	});

  	const emit = defineEmits(['product-updated']);
  	const user = ref(null);
  	const back_errors = ref({});
  	const isLoggedIn = computed(() => user.value !== null);

  	const formValues = ref({
		cart_id: '',
		product_id: '',
		quantity: '1'
  	});

  	const closeModal = props.closeModal;

  	const getUser = async () => {
		try {
	  		const response = await axios.get('/users/user');
	  		user.value = response.data;
	  		formValues.value.cart_id = user.value.cart.id;
	  		formValues.value.product_id = props.product.id;
		} catch (error) {
	  		user.value = null;
		}
  	};

  	onMounted(() => {
		getUser();
  	});

  	const handleSubmit = async () => {
		try {
	  		if (!isLoggedIn.value) return;
	  		await axios.post('/cartproducts/store', formValues.value);
	  		await successMessage({ is_delete: false, reload: false });
	  		handleClose();
	  		emitter.emit('cart-updated');
	  		emit('product-updated');
		} catch (error) {
	  		back_errors.value = await handlerErrors(error);
		}
  	};

  	const resetData = () => {
		user.value = null;
		back_errors.value = {};
  	};

  	const handleClose = () => {
		resetData();
		closeModal();
  	};

  	onBeforeUnmount(() => {
		handleClose();
  	});
</script>
