<template>
    <div class="cart-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in cartItems" :key="item.product.id">
                    <td>
                        <img :src="getImageUrl(item.product.images?.[0])" :alt="item.product.name" width="50"
                             height="50" />
                    </td>
                    <td>{{ item.product.name }}</td>
                    <td>${{ item.product.price.toFixed(2) }}</td>
                    <td>
                        <input type="number" v-model="item.quantity" min="1" @change="updateQuantity(item)" />
                    </td>
                    <td>${{ (item.product.price * item.quantity).toFixed(2) }}</td>
                    <td>
                        <button @click="removeFromCart(item.product.id)">Удалить</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="cart-total">
            <strong>Общая сумма: ${{ totalPrice }}</strong>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cartItems: Array,
    },
    computed: {
        totalPrice() {
            return this.cartItems.reduce((sum, item) => sum + item.product.price * item.quantity, 0).toFixed(2);
        }
    },
    methods: {
        getImageUrl(image) {
            return image ? `/storage/img/${image}` : '/storage/img/default.png';
        },
        updateQuantity(item) {
            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    product_id: item.product.id,
                    quantity: item.quantity
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Ошибка обновления количества');
                    }
                });
        },
        removeFromCart(productId) {
            fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.cartItems = this.cartItems.filter(item => item.product.id !== productId);
                    }
                });
        }
    }
};
</script>

<style>
.cart-container {
    width: 100%;
    margin-top: 20px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th,
.cart-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.cart-total {
    margin-top: 10px;
    text-align: right;
    font-size: 18px;
}
</style>
