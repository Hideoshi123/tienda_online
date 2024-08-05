import './bootstrap'
import { createApp } from 'vue'
import vSelect from 'vue-select'

// Components ---------------------------------------------------
import TheBookList from './components/Book/TheBookList.vue'
import TheCategoryList from './components/Category/TheCategoryList.vue'
import BackendError from './components/Components/BackendError.vue'
import ProductHome from './components/Product/ProductHome.vue'
import ProductModalBuyer from './components/Product/ProductModalBuyer.vue'
import CartIcon from './components/Cart/CartIcon.vue'
import TheCartList from './components/Cart/TheCartList.vue'

const app = createApp({
	components: {
		TheBookList,
		TheCategoryList,
		ProductHome,
		ProductModalBuyer,
		CartIcon,
		TheCartList,
	}
})

app.component('v-select', vSelect)
app.component('backend-error', BackendError)
app.mount('#app')
