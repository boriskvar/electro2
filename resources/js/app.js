import './bootstrap';

// === Breeze (Alpine.js) ===
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// === Vue.js (Старый проект) ===
import { createApp } from 'vue';
import Product from './components/Product.vue'; // Импортируем компонент

const app = createApp({
    components: { Product },
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
            fetch('https://electro.local/api/products')
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

// Проверяем, есть ли `#app`, прежде чем монтировать Vue (чтобы не ломать страницы Breeze)
if (document.getElementById('app')) {
    app.mount('#app');
}

