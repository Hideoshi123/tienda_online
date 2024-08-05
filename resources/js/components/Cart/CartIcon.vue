<template>
	<div class="cart-icon">
	  <i class="fas fa-shopping-cart"></i>
	  <span v-if="cartQuantity > 0" class="badge">{{ cartQuantity }}</span>
	</div>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import axios from 'axios'; // Asegúrate de importar axios si no está importado
  import { emitter } from '@/helpers/eventBus';

  const cartQuantity = ref(0);

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
  };

  const updateCartQuantity = async (cartId) => {
	try {
	  const response = await axios.get(`/cart/${cartId}/quantity`);
	  cartQuantity.value = response.data.quantity;
	} catch (error) {
	  console.error('Error fetching cart quantity:', error);
	  cartQuantity.value = 0;
	}
  };

  const handleCartUpdate = () => {
	getUser();
  };

  onMounted(() => {
	getUser();
	emitter.on('cart-updated', handleCartUpdate); // Escucha el evento
  });

  onUnmounted(() => {
	emitter.off('cart-updated', handleCartUpdate); // Desescucha el evento
  });
  </script>

  <style scoped>
  .cart-icon {
	position: relative;
	font-size: 24px;
	display: flex;
	align-items: center;
	justify-content: flex-start;
  }

  .cart-icon i {
	font-size: 1.5em;
	color: #555; /* Color del icono del carrito */
  }

  .badge {
	position: absolute;
	top: -5px;
	right: -5px;
	background-color: #ff5722;
	color: white;
	border-radius: 50%;
	padding: 2px 6px; /* Ajusta el padding para hacer el círculo más pequeño */
	border: 2px solid white;
	font-size: 0.8em;
	font-weight: bold;
  }

  /* Responsive styles */
  @media (max-width: 768px) {
	.cart-icon {
	  font-size: 20px;
	}

	.cart-icon i {
	  font-size: 1.2em;
	}

	.badge {
	  font-size: 0.7em;
	  padding: 1px 4px;
	}
  }
  </style>
