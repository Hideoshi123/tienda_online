<template>
    <div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="createProduct">Crear Producto</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="product_table">
					<thead>
						<tr class="w-100">
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
							<th>Categoria</th>
							<th>Imagen</th>
                            <th>Acciones</th>
                          </tr>
					</thead>
					<tbody @click="handleAction">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div v-if="load_modal">
		<product-modal :product_data="product" @close_modal="closeModal" @reload_state="reloadState" />
	</div>
</template>
<script setup>
	import { ref, onMounted } from 'vue'
	import { handlerErrors, successMessage, deleteMessage } from '@/helpers/Alerts.js'
	import ProductModal from './ProductModal.vue';
	import HandlerModal from '@/helpers/HandlerModal.js'
    const table = ref(null)
    const product = ref(null)
    const {openModal, closeModal, load_modal} = HandlerModal()

    onMounted(()=>index());

    const index = () => mountedTable()

    const mountedTable = ()=>{
        table.value = $('#product_table').DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			order: [[0, 'asc']],
			autoWidth: false,
			dom: 'Bfrtip',
			buttons: ['pageLength', 'excel', 'pdf', 'copy'],
			ajax: `/products/get-all-dt`,
			columns: [
				{ data: 'capital_letters_name', name: 'name', orderable: true, searchable: true, title: 'Nombre' },
				{ data: 'format_description', name: 'description', orderable: true, searchable: true, title: 'Descripcion' },
            	{ data: 'price', name: 'price', orderable: true, searchable: true, title: 'Precio' },
            	{ data: 'stock', name: 'stock', orderable: true, searchable: true, title: 'cantidad' },
				{ data: 'category.capital_letter', name: 'category.name', orderable: true, searchable: true, title: 'Categoria' },
				{ data: 'file.route', name: 'file.route', orderable: false, searchable: false, title: 'Imagen',
                	render: (data, type, row, meta) => {
                    return `<img src="${data}" alt="Imagen del Producto" style="max-width: 100px; max-height: 100px;" />`;
                	}
            	},
				{
					name: 'action',
					orderable: false,
					searchable: false,
					title: 'Botones',
					render: (data, type, row, meta) => {
						return `<div class="d-flex justify-content-center" data-role='actions'>
	            					<button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="btn btn-warning btn-sm">
	              						<i class='fas fa-pencil-alt' data-id='${row.id}' role='edit'></i>
									</button>
	            					<button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="btn btn-danger btn-sm ms-1">
	            						<i class='fas fa-trash-alt' data-id='${row.id}' role='delete'></i>
									</button>
	          					</div>`
					}
				}
			]
		})
    }

    const createProduct = async ()=>{
        product.value = null
        await openModal('uni_product_modal')
    }

    const editProduct= async (id)=>{
        try {
			const { data } = await axios.get(`/products/${id}`)
			product.value = data.product
			await openModal('uni_product_modal')
		} catch (error) {
			await handlerErrors(error)
		}
    }

    const deleteProduct= async (id)=>{
        if(!await deleteMessage()) return
        try {
			await axios.delete(`/products/${id}`)
			await successMessage({ is_delete: true })
			reloadState()
		} catch (error) {
			await handlerErrors(error)
		}
    }

    const handleAction = (event)=>{
        const button = event.target
		const product_id = button.getAttribute('data-id')
		if (button.getAttribute('role') == 'edit') {
			editProduct(product_id)
		} else if (button.getAttribute('role') == 'delete') {
			deleteProduct(product_id)
		}
    }

    const reloadState = () =>{
        table.value.destroy()
        index()
    }
</script>
