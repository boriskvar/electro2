<template>
    <div class="row">
        <div
             v-for="product in products"
             :key="product.id"
             :class="viewType === 'grid' ? 'col-md-4 col-xs-6' : 'col-md-12'"
             @add-to-cart="handleAddToCart">
            <div class="product">
                <div class="product-img">
                    <img
                         :src="getImageUrl(product.images?.[0])"
                         :alt="product.name"
                         width="60"
                         height="60" />
                    <div class="product-label">
                        <span v-if="product.discount_percentage !== undefined && product.discount_percentage > 0"
                              class="sale">
                            -{{ Math.floor(product.discount_percentage) }}%
                        </span>
                        <span v-if="product.is_new" class="new">NEW</span>
                    </div>
                </div>
                <div class="product-body">
                    <p class="product-category">{{ product.category ? product.category.name : 'Категория не найдена' }}
                    </p>
                    <h3 class="product-name">
                        <a :href="`/product/${product.id}`">{{ product.name }}</a>
                    </h3>
                    <h4 class="product-price">
                        ${{ parseFloat(product.price).toFixed(2) }}
                        <del v-if="product.old_price" class="product-old-price">
                            ${{ parseFloat(product.old_price).toFixed(2) }}
                        </del>
                    </h4>
                    <div class="product-rating">
                        <i
                           v-for="n in 5"
                           :key="n"
                           class="fa"
                           :class="{
                            'fa-star': n <= Math.round(product.rating),
                            'fa-star-o': n > Math.round(product.rating),
                        }"></i>
                    </div>
                    <div class="product-btns">
                        <button class="add-to-wishlist" @click="addToWishlist(product)">
                            <i class="fa fa-heart-o"></i>
                            <span class="tooltipp">add to wishlist</span>
                        </button>
                        <button class="add-to-compare" @click="addToCompare(product)">
                            <i class="fa fa-exchange"></i>
                            <span class="tooltipp">add to compare</span>
                        </button>
                        <button class="quick-view" @click="quickView(product.id)">
                            <i class="fa fa-eye"></i>
                            <span class="tooltipp">quick view</span>
                        </button>
                    </div>
                </div>
                <div class="add-to-cart">
                    <button class="add-to-cart-btn" @click="addToCart(product)">
                        <i class="fa fa-shopping-cart"></i> add to cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно Быстрого просмотра -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog" aria-labelledby="quickViewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document"><!-- Используем modal-sm для уменьшенной ширины -->
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">{{ selectedProduct?.name }}</h5>
                    <!-- Кнопка закрытия (X) -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" v-if="selectedProduct && selectedAttributes">
                    <img :src="getImageUrl(selectedProduct.images[0])" class="img-fluid mb-3" alt="Product Image"
                         style="max-width: 80px; max-height: 80px;">

                    <!-- Показываем только цену отдельно -->
                    <p><strong>Цена:</strong> {{ selectedProduct.price }}</p>

                    <!-- Отображаем все атрибуты, кроме 'price' и 'image' -->
                    <div v-for="(value, key) in selectedAttributes" :key="key">
                        <p><strong>{{ key }}:</strong> {{ value }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Кнопка закрытия внизу -->
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                </div>

            </div>
        </div>
    </div>

</template>


<script>
export default {
    props: {
        products: Array,
        viewType: String,
    },
    inheritAttrs: false, // игнорировать все не определенные атрибуты
    data() {
        return {
            selectedProduct: null,  // Данные о выбранном товаре
            selectedAttributes: [],  // Характеристики товара
        };
    },
    methods: {
        getImageUrl(image) {
            return image ? `/storage/img/${image}` : '/storage/img/default.png';
        },

        // Метод добавления товара в быстрые просмотр
        async quickView(productId) {
            try {
                const response = await axios.get(`/api/products/${productId}/details`);
                this.selectedProduct = response.data.product;

                // Фильтруем атрибуты, исключая 'price' и 'image'
                this.selectedAttributes = Object.fromEntries(
                    Object.entries(response.data.attributes).filter(
                        ([key]) => key !== 'price' && key !== 'image'
                    )
                );

                // Вызов модального окна через jQuery и Bootstrap 3
                $('#quickViewModal').modal('show');
            } catch (error) {
                console.error('Ошибка при загрузке данных товара:', error);
            }
        },

        // Метод добавления товара в корзину
        addToCart(product) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ product_id: product.id }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('updateCart'));
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: 'Товар добавлен в корзину', type: 'success' },
                            })
                        );
                        location.reload();
                    } else {
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: data.message, type: 'error' },
                            })
                        );
                    }
                })
                .catch((error) => {
                    window.dispatchEvent(
                        new CustomEvent('showToast', {
                            detail: { message: 'Ошибка при добавлении товара', type: 'error' },
                        })
                    );
                });
        },

        // ✅ Метод добавления товара в Wishlist

        /*         addToWishlist(product) {
                    fetch('/my-account/wishlist/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json', // ← ОБЯЗАТЕЛЕН!
                        },
                        body: JSON.stringify({ product_id: product.id }),
                    })
                        .then((response) => {
                            if (response.status === 401) {
                                // Неавторизован — редиректим и выбрасываем исключение, чтобы не шли дальше в цепочку
                                window.location.href = '/login?wishlist_product_id=' + product.id;
                                throw new Error('Неавторизован'); // ← важный шаг!
                            }
                            return response.json();  // ← парсим JSON только если точно не 401
                        })
                        .then((data) => {
                            if (data.success) {
                                window.dispatchEvent(
                                    new CustomEvent('showToast', {
                                        detail: { message: 'Товар добавлен в Wishlist', type: 'success' },
                                    })
                                );
                                // Обновление (перезагрузка) страницы
                                location.reload(); // или обнови локальное состояние, если не хочешь перезагрузку
                            } else {
                                window.dispatchEvent(
                                    new CustomEvent('showToast', {
                                        detail: { message: data.message, type: 'error' },
                                    })
                                );
                            }
                        })
                        .catch((error) => {
                            if (error.message === 'Неавторизован') return; // ничего не делаем, это ожидаемое поведение
        
                            console.error('Ошибка при добавлении в Wishlist:', error);
                            window.dispatchEvent(
                                new CustomEvent('showToast', {
                                    detail: { message: 'Ошибка при добавлении в Wishlist', type: 'error' },
                                })
                            );
                        });
                }, 
        */

        // Метод добавления товара в сравнение
        addToCompare(product) {
            // Скрываем старые сообщения об ошибках, если они были
            window.dispatchEvent(
                new CustomEvent('hideToast')  // Даем сигнал, чтобы скрыть старое сообщение
            );

            fetch('/my-account/compare/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json', // ← ОБЯЗАТЕЛЕН!
                },
                body: JSON.stringify({ product_id: product.id }),
            })
                .then(response => {
                    if (response.status === 401) {
                        // Неавторизован — перенаправляем на страницу логина с параметром
                        window.location.href = '/login?compare_product_id=' + product.id;
                        throw new Error('Неавторизован'); // Важно выбрасывать исключение, чтобы не продолжать дальнейшее выполнение
                    }

                    return response.json(); // ← парсим JSON только если точно не 401
                })
                .then((data) => {
                    if (data.success) {
                        // Показываем сообщение о том, что товар добавлен
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: 'Товар добавлен в сравнение', type: 'success' },
                            })
                        );
                        location.reload();
                    } else {
                        // Показываем ошибку, если что-то пошло не так на сервере
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: data.message, type: 'error' },
                            })
                        );
                    }
                })
                .catch((error) => {
                    if (error.message === 'Неавторизован') return; // Ожидаемое поведение для 401, ничего не делаем

                    console.error('Ошибка при добавлении в сравнение:', error);
                    window.dispatchEvent(
                        new CustomEvent('showToast', {
                            detail: { message: 'Ошибка при добавлении в сравнение', type: 'error' },
                        })
                    );
                });
        },
    },
};
</script>
