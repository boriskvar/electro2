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
                        <button class="add-to-compare">
                            <i class="fa fa-exchange"></i>
                            <span class="tooltipp">add to compare</span>
                        </button>
                        <button class="quick-view">
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
</template>


<script>
export default {
    props: {
        products: Array,
        viewType: String,
    },
    methods: {
        getImageUrl(image) {
            return image ? `/storage/img/${image}` : '/storage/img/default.png';
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

        // Метод добавления товара в Wishlist
        addToWishlist(product) {
            fetch('/account/wishlist/store', {
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
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: 'Товар добавлен в Wishlist', type: 'success' },
                            })
                        );
                        // Обновление страницы
                        location.reload(); // Обновляем страницу после успешного добавления товара
                    } else {
                        window.dispatchEvent(
                            new CustomEvent('showToast', {
                                detail: { message: data.message, type: 'error' },
                            })
                        );
                    }
                })
                .catch((error) => {
                    console.error('Ошибка при добавлении в Wishlist:', error);
                    window.dispatchEvent(
                        new CustomEvent('showToast', {
                            detail: { message: 'Ошибка при добавлении в Wishlist', type: 'error' },
                        })
                    );
                });
        },
    },
};
</script>