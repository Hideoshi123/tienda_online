<template>
	<div class="cart-icon">
	  	<i class="fas fa-shopping-cart"></i>
	  	<span v-if="cartQuantity > 0" class="badge">{{ cartQuantity }}</span>
	</div>
</template>
<script setup>
	import { ref, onMounted, onUnmounted } from 'vue'
  	import { emitter } from '@/helpers/eventBus'

  	const cartQuantity = ref(0)

  	const getUser = async () => {
		try {
	  		const response = await axios.get('/users/user');
	  		const user = response.data;
	  		if (user.cart && user.cart.id) {
				await updateCartQuantity(user.cart.id);
	  		} else {
				cartQuantity.value = 0;
	  		}
		} catch (error) {
	  		console.error('Error fetching user data:', error);
		}
  	}

  	const updateCartQuantity = async (cartId) => {
		try {
	  		const response = await axios.get(`/cart/${cartId}/quantity`);
	  		cartQuantity.value = response.data.quantity;
		} catch (error) {
	  		console.error('Error fetching cart quantity:', error);
	  		cartQuantity.value = 0;
		}
  	}

  	const handleCartUpdate = () => {
		getUser()
  	}

  	onMounted(() => {
		getUser()
		emitter.on('cart-updated', handleCartUpdate) // Escucha el evento
 	});

  	onUnmounted(() => {
		emitter.off('cart-updated', handleCartUpdate) // Desescucha el evento
	});
</script>
