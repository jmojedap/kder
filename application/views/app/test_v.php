<script>
	$(document).ready(function(){
		$('#btn_test').click(function(){
			console.log('hola mundo desde botón');
			$('#contenido').html('<h1>Modificando contenido</h1>');
			toastr['success']('Bien hecho!');
		});		
	});
</script>

<button class="btn btn-success" id="btn_test">
	Actualizar
</button>

<div id="contenido">
	contenido inicial
</div>

<div id="test">
	<h3>{{ text }}</h3>
	<table class="table bg-white">
		<tbody>
			<tr v-for="(picture, key) in pictures">
				<td>
					{{ picture.id }}
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script>
	new Vue({
		el: '#test',
		created: function(){
			//this.get_list();
		},
		data: {
			text: 'el nuevo texto con vue',
			pictures: <?php echo json_encode($pictures->result()); ?>
		},
		methods: {
			
		}
	});
</script>