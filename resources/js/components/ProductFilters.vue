<template>
    <div class="product-filters">
        <!-- Sort By -->
        <div class="store-sort">
            <label>
                Sort By:
                <select
                    v-model="localSort"
                    class="input-select"
                    @change="updateSort"
                >
                    <option value="position">Position</option>
                    <option value="rating">Popular</option>
                </select>
            </label>

            <!-- Show -->
            <label>
                Show:
                <select
                    v-model="localShow"
                    class="input-select"
                    @change="updateShow"
                >
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </label>
        </div>

        <!-- Toggle View -->
        <ul class="store-grid">
            <li
                :class="{ active: view === 'grid' }"
                @click="toggleView('grid')"
            >
                <i class="fa fa-th"></i>
            </li>
            <li
                :class="{ active: view === 'list' }"
                @click="toggleView('list')"
            >
                <i class="fa fa-th-list"></i>
            </li>
        </ul>

        <!-- Pagination -->
        <div class="store-filter clearfix">
            <span class="store-qty">
                Showing {{ (currentPage - 1) * localShow + 1 }}-{{
                    Math.min(currentPage * localShow, total)
                }}
                products
            </span>
            <ul class="store-pagination">
                <li
                    :class="{ active: currentPage === 1 }"
                    @click="changePage(currentPage - 1)"
                    v-if="currentPage > 1"
                >
                    <a href="#"><i class="fa fa-angle-left"></i></a>
                </li>
                <li
                    v-for="page in pageNumbers"
                    :key="page"
                    :class="{ active: currentPage === page }"
                    @click="changePage(page)"
                >
                    <a href="#">{{ page }}</a>
                </li>
                <li
                    :class="{ active: currentPage === lastPage }"
                    @click="changePage(currentPage + 1)"
                    v-if="currentPage < lastPage"
                >
                    <a href="#"><i class="fa fa-angle-right"></i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sort: { type: String, default: "position" },
        show: { type: Number, default: 20 },
        currentPage: { type: Number, default: 1 },
        total: { type: Number, default: 0 },
        view: { type: String, default: "grid" },
    },
    data() {
        return {
            localSort: this.sort,
            localShow: this.show,
        };
    },
    computed: {
        pageNumbers() {
            const totalPages = Math.ceil(this.total / this.localShow);
            return Array.from({ length: totalPages }, (_, i) => i + 1);
        },
        lastPage() {
            return Math.ceil(this.total / this.localShow);
        },
    },
    methods: {
        updateSort() {
            this.$emit("update:sort", this.localSort);
        },
        updateShow() {
            this.$emit("update:show", this.localShow);
        },
        changePage(page) {
            if (page > 0 && page <= this.lastPage) {
                this.$emit("update:page", page);
            }
        },
        toggleView(view) {
            this.$emit("update:view", view);
        },
    },
};
</script>
