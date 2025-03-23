<template>
    <div class="col-md-4 col-xs-6">
        <div class="product">
            <div class="product-img">
                <img
                    :src="getImageUrl(product.image)"
                    :alt="product.name"
                    width="60"
                    height="60"
                />
                <div class="product-label">
                    <span v-if="product.discount" class="sale"
                        >-{{ product.discount }}%</span
                    >
                    <span v-if="product.isNew" class="new">NEW</span>
                </div>
            </div>
            <div class="product-body">
                <p class="product-category">{{ product.category }}</p>
                <h3 class="product-name">
                    <a :href="`/product/${product.id}`">{{ product.name }}</a>
                </h3>
                <h4 class="product-price">
                    ${{ parseFloat(product.price).toFixed(2) }}
                    <del v-if="product.oldPrice" class="product-old-price">
                        ${{ parseFloat(product.oldPrice).toFixed(2) }}
                    </del>
                </h4>
            </div>
            <div class="add-to-cart">
                <button class="add-to-cart-btn" @click="addToCart">
                    <i class="fa fa-shopping-cart"></i> add to cart
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        console.log("Product data:", this.product); // Проверка данных товара
    },
    methods: {
        getImageUrl(image) {
            return image && image.length > 0
                ? `/storage/img/${image}`
                : "/storage/img/default.png";
        },
        addToCart() {
            this.$emit("add-to-cart", this.product); // Эмитируем событие для родителя
        },
    },
};
</script>
