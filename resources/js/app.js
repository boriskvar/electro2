import './bootstrap';

// === Breeze (Alpine.js) ===
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// === Vue.js ===
import { createApp } from 'vue';
import Product from './components/Product.vue';


// 📌 Магазин (Vue монтируется на #app и загружает товары через API)
if (document.getElementById('app')) {
    const app = createApp({
        components: { Product },
        data() {
            return {
                products: []
            };
        },
        mounted() {
            this.loadProducts();
        },
        methods: {
            loadProducts() {
                fetch('/api/products')
                    .then(response => response.json())
                    .then(data => {
                        this.products = data.data;
                    })
                    .catch(error => console.error('Ошибка загрузки продуктов:', error));
            }
        }
    });

    app.mount('#app');
}

// 📌 Wishlist (Vue монтируется на #wishlist-products и получает данные от Laravel)
if (document.getElementById('wishlist-products')) {
    const wishlistApp = createApp({
        components: { Product },
        data() {
            return {
                products: []
            };
        },
        mounted() {
            // Получаем данные из Laravel
            const element = document.getElementById('wishlist-products');
            this.products = JSON.parse(element.dataset.products);
        }
    });

    wishlistApp.mount('#wishlist-products');
}



