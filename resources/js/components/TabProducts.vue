<template>
    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">New Products</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li
                                    v-for="(category, index) in categories"
                                    :key="index"
                                    :class="{ active: activeTab === category }"
                                    @click="selectTab(category)"
                                >
                                    <a href="javascript:void(0)">{{
                                        category
                                    }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab1" class="tab-pane active">
                                <div
                                    class="products-slick slick-initialized slick-slider"
                                    data-nav="#slick-nav-1"
                                >
                                    <!-- product -->
                                    <div
                                        class="product"
                                        v-for="product in filteredProducts"
                                        :key="product.id"
                                    >
                                        <div class="product-img">
                                            <img
                                                :src="product.image"
                                                :alt="product.name"
                                            />
                                            <div
                                                class="product-label"
                                                v-if="
                                                    product.isOnSale ||
                                                    product.isNew
                                                "
                                            >
                                                <span
                                                    v-if="product.isOnSale"
                                                    class="sale"
                                                    >-{{
                                                        product.discount
                                                    }}%</span
                                                >
                                                <span
                                                    v-if="product.isNew"
                                                    class="new"
                                                    >NEW</span
                                                >
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">
                                                {{ product.category }}
                                            </p>
                                            <h3 class="product-name">
                                                <a href="#">{{
                                                    product.name
                                                }}</a>
                                            </h3>
                                            <h4 class="product-price">
                                                ${{ product.price }}
                                                <del
                                                    class="product-old-price"
                                                    v-if="product.oldPrice"
                                                    >${{
                                                        product.oldPrice
                                                    }}</del
                                                >
                                            </h4>
                                            <div class="product-rating">
                                                <i
                                                    class="fa"
                                                    :class="
                                                        product.rating >= 1
                                                            ? 'fa-star'
                                                            : 'fa-star-o'
                                                    "
                                                ></i>
                                                <i
                                                    class="fa"
                                                    :class="
                                                        product.rating >= 2
                                                            ? 'fa-star'
                                                            : 'fa-star-o'
                                                    "
                                                ></i>
                                                <i
                                                    class="fa"
                                                    :class="
                                                        product.rating >= 3
                                                            ? 'fa-star'
                                                            : 'fa-star-o'
                                                    "
                                                ></i>
                                                <i
                                                    class="fa"
                                                    :class="
                                                        product.rating >= 4
                                                            ? 'fa-star'
                                                            : 'fa-star-o'
                                                    "
                                                ></i>
                                                <i
                                                    class="fa"
                                                    :class="
                                                        product.rating >= 5
                                                            ? 'fa-star'
                                                            : 'fa-star-o'
                                                    "
                                                ></i>
                                            </div>
                                            <div class="product-btns">
                                                <button class="add-to-wishlist">
                                                    <i class="fa fa-heart-o"></i
                                                    ><span class="tooltipp"
                                                        >add to wishlist</span
                                                    >
                                                </button>
                                                <button class="add-to-compare">
                                                    <i
                                                        class="fa fa-exchange"
                                                    ></i
                                                    ><span class="tooltipp"
                                                        >add to compare</span
                                                    >
                                                </button>
                                                <button class="quick-view">
                                                    <i class="fa fa-eye"></i
                                                    ><span class="tooltipp"
                                                        >quick view</span
                                                    >
                                                </button>
                                            </div>
                                        </div>
                                        <div class="add-to-cart">
                                            <button
                                                class="add-to-cart-btn"
                                                @click="
                                                    handleAddToCart(product)
                                                "
                                            >
                                                <i
                                                    class="fa fa-shopping-cart"
                                                ></i>
                                                add to cart
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /product -->
                                </div>
                                <div
                                    id="slick-nav-1"
                                    class="products-slick-nav"
                                ></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->
</template>

<script>
import NewProduct from "@/components/NewProduct.vue";

export default {
    components: {
        NewProduct,
    },
    data() {
        return {
            categories: ["Laptops", "Smartphones", "Cameras", "Accessories"],
            activeTab: "Laptops",
            products: [],
            loading: false, // Флаг загрузки данных
        };
    },
    computed: {
        filteredProducts() {
            return this.products.filter(
                (product) => String(product.category) === String(this.activeTab)
            );
        },
    },
    methods: {
        selectTab(category) {
            this.activeTab = category;
        },
        fetchProducts() {
            this.loading = true;
            fetch("/api/products?isNew=1")
                .then((response) => response.json())
                .then((data) => {
                    if (data.data && Array.isArray(data.data)) {
                        this.products = data.data.map((item) => ({
                            id: item.id,
                            name: item.name,
                            category: item.category || "Unknown",
                            price: item.price,
                            oldPrice: item.oldPrice || null,
                            image: item.image || "default.png",
                            isOnSale: Boolean(item.isOnSale),
                            isNew: Boolean(item.isNew),
                            rating: item.rating || 0,
                            discount: item.discount || 0,
                        }));
                    } else {
                        console.error("Некорректный формат данных:", data);
                    }
                })
                .catch((error) =>
                    console.error("Ошибка загрузки продуктов:", error)
                )
                .finally(() => {
                    this.loading = false;
                });
        },
        handleAddToCart(product) {
            console.log(`Добавлен в корзину: ${product.name}`);
        },
    },
    mounted() {
        this.fetchProducts();
    },
};
</script>
