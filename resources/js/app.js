import './bootstrap';

// Инициализация Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Инициализация Vue.js
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

app.mount('#app');

