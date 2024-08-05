<template>
	<div v-if="loading" class="text-center mt-4">
	  <div class="spinner-border text-primary" role="status">
		<span class="visually-hidden">Cargando...</span>
	  </div>
	</div>

	<div v-else>
	  <div v-if="listProducts.length > 0">
		<div
		  class="bg-light p-4 border rounded mb-4"
		  v-for="(item, index) in listProducts"
		  :key="index"
		>
		  <div class="d-flex">
			<img :src="item.product.file.route" class="img-fluid" style="width: 150px; height: auto; object-fit: cover;">
			<div class="ms-4 flex-grow-1">
			  <h4 class="mb-3 text-primary">{{ item.product.capital_letters_name }}</h4>
			  <p>{{ item.product.capital_letter_description }}</p>
			  <div v-if="item.product.stock !== 0">
				<p class="mb-3"><strong>Stock:</strong> {{ item.product.stock }}</p>
			  </div>
			  <p class="mb-3"><strong>Price:</strong> {{ item.product.price }} $</p>

			  <!-- Hidden Form -->
			  <form @submit.prevent="updateCart(item)">
				<BackendError :errors="backErrors" />
				<input type="hidden" v-model="item.cart_id" />
				<input type="hidden" v-model="item.product_id" />
				<input type="hidden" v-model="item.quantity" />
				<!-- Quantity Controls -->
				<div class="input-group mb-3">
				  <button type="button" class="btn btn-outline-secondary" @click="decreaseQuantity(item)">-</button>
				  <input type="number" class="form-control" v-model.number="item.quantity" :min="1" :max="getMaxQuantity(item)" readonly>
				  <button type="button" class="btn btn-outline-secondary" @click="increaseQuantity(item)">+</button>
				</div>
				<!-- Update Button -->
				<button type="submit" class="btn btn-primary w-100 mb-2">
				  Actualizar carrito
				  <i class="fas fa-shopping-cart"></i>
				</button>
				<!-- Delete Button -->
				<button type="button" class="btn btn-danger w-100" @click="removeFromCart(item)">
				  Eliminar del carrito
				  <i class="fas fa-trash"></i>
				</button>
			  </form>
			</div>
		  </div>
		</div>
	  </div>
	  <div v-else>
		<p class="alert alert-info">No hay productos en el carrito.</p>
	  </div>
	</div>
  </template>

  <script setup>
  import { emitter } from '@/helpers/eventBus';
  import { ref, onMounted } from 'vue';
  import { handlerErrors, successMessage, deleteMessage  } from '@/helpers/Alerts';
  import BackendError from '../Components/BackendError.vue';

  const listProducts = ref([]);
  const backErrors = ref({});
  const loading = ref(true);
  const maxQuantities = ref({});

  const getUser = async () => {
	loading.value = true;
	try {
	  const response = await axios.get('/users/user');
	  const user = response.data;
	  if (user.cart && user.cart.id) {
		await containerCart(user.cart.id);
	  } else {
		listProducts.value = [];
	  }
	} catch (error) {
	  console.error('Error fetching user data:', error);
	} finally {
	  loading.value = false;
	}
  };

  const containerCart = async (cartId) => {
	loading.value = true;
	try {
	  const response = await axios.get(`/cart/${cartId}/edit`);
	  listProducts.value = response.data;

	  // Calculate the initial max quantities for each item
	  maxQuantities.value = response.data.reduce((acc, item) => {
		acc[item.product.id] = item.product.stock + item.quantity;
		return acc;
	  }, {});
	} catch (error) {
	  console.error('Error fetching cart quantity:', error);
	  listProducts.value = [];
	} finally {
	  loading.value = false;
	}
  };

  const updateCart = async (item) => {
	loading.value = true;
	try {
	  await axios.post(`/cartproducts/${item.id}/update`, {
		cart_id: item.cart_id,
		product_id: item.product_id,
		quantity: item.quantity,
	  });
	  await successMessage({ message: 'Carrito actualizado' });
	  emitter.emit('cart-updated');
	  await getUser(); // Recargar el componente
	} catch (error) {
	  backErrors.value = await handlerErrors(error);
	} finally {
	  loading.value = false;
	}
  };

  const removeFromCart = async (item) => {
	if(!await deleteMessage()) return
	loading.value = true;
	try {
	  await axios.post(`/cartproducts/${item.id}/delete`, {
		cart_id: item.cart_id,
		product_id: item.product_id,
	  });
	  await successMessage({ is_delete: true, reload: false });
	  emitter.emit('cart-updated');
	  await getUser(); // Recargar el componente
	} catch (error) {
	  backErrors.value = await handlerErrors(error);
	} finally {
	  loading.value = false;
	}
  };

  const increaseQuantity = (item) => {
	if (item.quantity < getMaxQuantity(item)) {
	  item.quantity++;
	}
  };

  const decreaseQuantity = (item) => {
	if (item.quantity > 1) {
	  item.quantity--;
	}
  };

  // Gets the max quantity for an item
  const getMaxQuantity = (item) => {
	return maxQuantities.value[item.product.id] || item.product.stock;
  };

  onMounted(() => {
	getUser();
  });
  </script>
