<template>
    <div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog" aria-labelledby="quickViewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <!-- Условие для отображения имени товара, если оно есть -->
                    <h5 class="modal-title" id="quickViewModalLabel" v-if="selectedProduct">{{ selectedProduct.name }}
                    </h5>

                    <!-- Кнопка закрытия модального окна -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" v-if="selectedProduct && selectedAttributes">
                    <!-- Изображение товара с корректным путём -->
                    <img :src="getImageUrl(selectedProduct.images[0])" class="img-fluid mb-3" alt="Product Image"
                         style="max-width: 80px;">
                    <p><strong>Цена:</strong> {{ selectedProduct.price }}</p>

                    <!-- Атрибуты товара -->
                    <div v-for="(value, key) in selectedAttributes" :key="key">
                        <p><strong>{{ key }}:</strong> {{ value }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Кнопка для закрытия модалки -->
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedProduct: null,  // Для хранения выбранного товара
            selectedAttributes: null,  // Для хранения атрибутов товара
        };
    },
    methods: {
        // Метод для загрузки данных о товаре по ID
        async quickView(productId) {
            console.log('Запрос на товар ID:', productId);

            try {
                // Запрос на API для получения данных товара
                const response = await axios.get(`/api/products/${productId}/details`);
                this.selectedProduct = response.data.product;
                this.selectedAttributes = response.data.attributes;

                // Открытие модального окна
                $('#quickViewModal').modal('show');
            } catch (error) {
                console.error('Ошибка при загрузке данных товара:', error);
            }
        },
        // Метод для получения корректного пути к изображению
        getImageUrl(image) {
            // Проверка, если изображение не пустое
            if (image) {
                return `/storage/img/${image}`;  // Корректный путь к изображению
            }
            return '/storage/img/default.png';  // Запасное изображение, если товара нет
        },
    },
};
</script>

<style scoped>
/* Добавьте стили для модального окна, если необходимо */
</style>
