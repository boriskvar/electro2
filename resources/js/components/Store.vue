<template>
    <div>
        <!-- Продукты -->
        <div>
            <!-- Сообщение, если продуктов нет -->
            <p v-if="!products || products.length === 0">
                No products available.
            </p>
            <!-- Список продуктов -->
            <div v-else class="row">
                <product
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                    @add-to-cart="handleAddToCart"
                    class="col-3 col-sm-3 col-md-3 col-lg-4 col-xl-4"
                >
                    <img
                        :src="`/storage/img/${product.image}`"
                        alt="Product Image"
                    />
                </product>
            </div>
        </div>

        <!-- Фильтры -->
        <product-filters
            :sort="filters.sortBy"
            :show="Number(filters.perPage)"
            :current-page="currentPage"
            :total="total"
            :view="viewMode"
            @update:sort="updateSort"
            @update:show="updateShow"
            @update:page="updatePage"
            @update:view="updateView"
        />
    </div>
</template>

<script>
import ProductFilters from "./ProductFilters.vue";
import Product from "./Product.vue";

export default {
    components: {
        ProductFilters,
        Product,
    },
    data() {
        return {
            products: [],
            filters: {
                sortBy: "position", // Сортировка по умолчанию
                perPage: 20, // Количество товаров на странице
            },
            currentPage: 1, // Текущая страница
            total: 0, // Общее количество продуктов
            viewMode: "grid", // Вид (grid или list)
        };
    },
    methods: {
        async loadProducts() {
            try {
                // Имитация API-запроса через fetch
                const response = await fetch(
                    `/api/products?sort=${this.filters.sortBy}&perPage=${this.filters.perPage}&page=${this.currentPage}`
                );
                const data = await response.json();
                this.products = data.data; // Продукты
                this.total = data.total; // Общее количество продуктов
            } catch (error) {
                console.error("Ошибка загрузки продуктов:", error);
            }
        },
        handleAddToCart(product) {
            console.log(`Добавлено в корзину: ${product.name}`);
        },
        updateSort(sortBy) {
            this.filters.sortBy = sortBy;
            this.loadProducts();
        },
        updateShow(perPage) {
            this.filters.perPage = perPage;
            this.loadProducts();
        },
        updatePage(page) {
            this.currentPage = page;
            this.loadProducts();
        },
        updateView(view) {
            this.viewMode = view;
        },
    },
    mounted() {
        this.loadProducts(); // Загрузка продуктов при монтировании компонента
    },
};
</script>
