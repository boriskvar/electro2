<!-- Модальное окно -->
<template>
    <div id="quickViewModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">{{ selectedProduct.name }}</h5>
                    <button type="button" class="close" @click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="selectedProduct" class="product-details">
                        <img :src="getImageUrl(selectedProduct.images[0])" alt="Product Image" class="img-responsive">
                        <h3>{{ selectedProduct.name }}</h3>
                        <h4>{{ selectedProduct.price }}</h4>

                        <div v-for="(value, attributeName) in selectedAttributes" :key="attributeName">
                            <h5>{{ attributeName }}</h5>
                            <p>{{ value }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            productData: Object, // Получаем данные продукта и характеристик через пропс
        }
        , data() {
            return {
                selectedProduct: this.productData.product
                , selectedAttributes: this.productData.attributes
            , };
        }
        , methods: {
            getImageUrl(image) {
                // Метод для получения URL изображения, если нужно
                return `/storage/images/${image}`;
            }
            , closeModal() {
                // Закрытие модального окна
                $('#quickViewModal').modal('hide');
            }
        , }
        , mounted() {
            // Открытие модального окна при монтировании компонента
            $('#quickViewModal').modal('show');
        }
    };
</script>


<style>
    .product-widget .product-body {
        padding-left: 0 !important;
    }

    /* Стиль для заголовков характеристик */
    .attribute-header {
        font-weight: 700;
        line-height: 1;
        background-color: #f5f5f5;
        /* Цвет фона для заголовка */
        color: #333;
        /* Цвет текста */
        padding: 12px 0;
        /* Отступы сверху и снизу */
        margin-top: 10px;
        /* Отступ сверху от предыдущего элемента */
        text-align: center;
        /* Центрирование текста */
        border-radius: 5px;
        /* Скругленные углы */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        /* Легкая тень */
    }
</style>