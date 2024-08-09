import './bootstrap'
import { createApp } from 'vue'
import vSelect from 'vue-select'

// Components ---------------------------------------------------
import TheCategoryList from './components/Categories/TheCategoryList.vue'
import BackendError from './components/Components/BackendError.vue'
import ProductHome from './components/Product/ProductHome.vue'
import ProductModalBuyer from './components/Product/ProductModalBuyer.vue'
import CartIcon from './components/Cart/CartIcon.vue'
import TheCartList from './components/Cart/TheCartList.vue'
import TheProductList from './components/Product/TheProductList.vue'
import ProductModal from './components/Product/ProductModal.vue'
import ProductForCategory from './components/Product/ProductForCategory.vue'

const app = createApp({
	components: {
		ProductHome,
		ProductModalBuyer,
		CartIcon,
		TheCartList,
        TheCategoryList,
		TheProductList,
		ProductModal,
		ProductForCategory,
	}
})

app.component('v-select', vSelect)
app.component('backend-error', BackendError)
app.mount('#app')
