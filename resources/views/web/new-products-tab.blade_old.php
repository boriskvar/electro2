<div id="new-products">
    <div class="section-nav">
        <ul class="section-tab-nav tab-nav">
            <li :class="{ active: activeTab === 'laptops' }" @click.prevent="switchTab('laptops')">
                <a href="#">Laptops</a>
            </li>
            <li :class="{ active: activeTab === 'smartphones' }" @click.prevent="switchTab('smartphones')">
                <a href="#">Smartphones</a>
            </li>
            <li :class="{ active: activeTab === 'cameras' }" @click.prevent="switchTab('cameras')">
                <a href="#">Cameras</a>
            </li>
            <li :class="{ active: activeTab === 'accessories' }" @click.prevent="switchTab('accessories')">
                <a href="#">Accessories</a>
            </li>
        </ul>
    </div>

    <!-- Передаем активную вкладку в компонент -->
    <new-products :active-tab="activeTab"></new-products>
</div>