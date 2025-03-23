<template>
    <div class="pagination">
        <button
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
        >
            Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            :disabled="currentPage === totalPages"
            @click="goToPage(currentPage + 1)"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    props: {
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
    },
    methods: {
        goToPage(page) {
            if (page > 0 && page <= this.totalPages) {
                this.$emit("page-changed", page);
            }
        },
    },
};
</script>
