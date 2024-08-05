<template>
    <div class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <span v-if="cartQuantity > 0" class="badge">{{ cartQuantity }}</span>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
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
    font-size: 24px; /* Aumenta el tamaño del icono */
}

.cart-icon i {
    font-size: 1.5em; /* Aumenta el tamaño del icono */
}

.badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: red;
    color: black;
    border-radius: 50%;
    padding: 2px 6px; /* Reducir el padding para hacer el círculo más pequeño */
    border: 1px solid red; /* Reducir el tamaño del borde */
    font-size: 0.8em; /* Ajustar el tamaño del texto */
}
</style>

