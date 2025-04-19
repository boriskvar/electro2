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

// === Wishlist ===
document.addEventListener('DOMContentLoaded', function () {
    console.log('Скрипт wishlist загружен');

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.add-to-wishlist');
        if (!button) return;

        e.preventDefault();

        const productId = button.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        console.log('Добавляем в список желаний продукт с ID:', productId);

        fetch('/my-account/wishlist/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        })
            .then((response) => {
                if (response.status === 401) {
                    window.location.href = '/login?wishlist_product_id=' + productId;
                    throw new Error('Неавторизован');
                }
                return response.json();
            })
            .then((data) => {
                if (!data) return;

                if (data.success) {
                    alert(data.message || 'Товар добавлен в список желаний!');

                    // Отключаем кнопку
                    button.classList.add('disabled');
                    button.style.pointerEvents = 'none';
                    button.style.opacity = '0.5';

                } else {
                    alert('Ошибка: ' + (data.message || 'Не удалось добавить в Wishlist'));
                }
            })
            .catch((error) => {
                if (error.message === 'Неавторизован') return;

                console.error('Ошибка при добавлении в wishlist:', error);
                window.dispatchEvent(
                    new CustomEvent('showToast', {
                        detail: { message: 'Ошибка при добавлении в Wishlist', type: 'error' },
                    })
                );
            });
    });
});


// === Compare ===
document.addEventListener('DOMContentLoaded', function () {
    console.log('Скрипт compare загружен');

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.add-to-compare');

        if (!button) return;

        e.preventDefault();
        const productId = button.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        console.log('Добавляем в сравнение продукт с ID:', productId);
        console.log('ID товара:', productId);

        fetch('/my-account/compare/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        })
            .then((response) => {
                if (response.status === 401) {
                    window.location.href = '/login?compare_product_id=' + productId;
                    throw new Error('Неавторизован');
                }
                return response.json();
            })
            .then((data) => {
                if (!data) return;

                if (data.success) {
                    alert(data.message || 'Товар добавлен в список сравнений!');

                    // Отключаем кнопку
                    button.classList.add('disabled');
                    button.style.pointerEvents = 'none';
                    button.style.opacity = '0.5';

                } else {
                    alert('Ошибка: ' + (data.message || 'Не удалось добавить в сравнение'));
                }
            })
            .catch((error) => {
                if (error.message === 'Неавторизован') return;

                console.error('Ошибка при добавлении в сравнение:', error);
                window.dispatchEvent(
                    new CustomEvent('showToast', {
                        detail: { message: 'Ошибка при добавлении в Сравнение', type: 'error' },
                    })
                );
            });
    });
});

