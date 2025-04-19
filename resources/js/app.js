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
/* if (document.getElementById('wishlist-products')) {
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
} */

// === Wishlist ===
/* document.addEventListener('DOMContentLoaded', function () {
    console.log('Скрипт загружен');

    const tabsContainer = document.querySelector('.products-tabs');
    if (tabsContainer) {
        tabsContainer.addEventListener('click', function (e) {
            if (e.target.closest('.add-to-wishlist')) {
                e.preventDefault();

                const button = e.target.closest('.add-to-wishlist');
                const productId = button.dataset.id;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                            button.innerHTML = '<i class="fa fa-heart-o"></i> added';
                            button.classList.add('disabled');
                        } else {
                            alert('Ошибка: ' + (data.message || 'Не удалось добавить в Wishlist'));
                        }
                    })
                    .catch((error) => {
                        if (error.message === 'Неавторизован') return; // ничего не делаем, это ожидаемое поведение

                        console.error('Ошибка при добавлении в wishlist:', error);
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: 'Ошибка при добавлении в Wishlist', type: 'error' },
                            })
                        );
                    });

            }
        });
    }
}); */

// === Wishlist ===
document.addEventListener('DOMContentLoaded', function () {
    console.log('Скрипт wishlist загружен');

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.add-to-wishlist');
        if (!button) return;

        e.preventDefault();

        const productId = button.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    button.innerHTML = '<i class="fa fa-heart-o"></i> added';
                    button.classList.add('disabled');
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

