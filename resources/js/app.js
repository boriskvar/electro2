import './bootstrap';

// === Breeze (Alpine.js) ===
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// === Vue.js ===
import { createApp } from 'vue';
import Product from './components/Product.vue';
import QuickViewModal from './components/QuickViewModal.vue';

// 📌 Магазин (Vue монтируется на #app и загружает товары через API)
if (document.getElementById('app')) {
    const app = createApp({
        components: { Product, QuickViewModal },
        data() {
            return {
                products: [] // Инициализируем пустым массивом
            };
        },
        mounted() {
            this.loadProducts(); // Загружаем данные при монтировании
        },
        methods: {
            loadProducts() {
                fetch('/api/products')
                    .then(response => response.json())
                    .then(data => {
                        this.products = data.data; // Записываем полученные продукты
                    })
                    .catch(error => console.error('Ошибка загрузки продуктов:', error));
            }
        }
    });

    // 📌 Глобальная регистрация компонента
    app.component('products', Product);
    app.component('quick-view-modal', QuickViewModal);

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



