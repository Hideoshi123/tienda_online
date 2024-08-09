<x-app title="Categorías">
    <section class="container">
        <!-- Pasar el ID de la categoría como una prop al componente Vue -->
        <product-for-category :category-id="{{ $categoryId }}" />
    </section>
</x-app>
