<?php if ($_settings->chk_flashdata('success')) : ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline rounded-0 card-primary">
	<div class="card-header">
		<h3 class="card-title">Publicaciones</h3>
		<div class="card-tools">
			<a href="./?page=articles/manage_article" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Crear Publicación</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<?php if ($_settings->userdata('type') != 1) : ?>
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="25%">
						<col width="35%">
						<col width="10%">
						<col width="15%">
					</colgroup>
				<?php else : ?>
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="20%">
						<col width="25%">
						<col width="15%">
						<col width="10%">
						<col width="15%">
					</colgroup>
				<?php endif; ?>

				<thead>
					<tr>
						<th>#</th>
						<th>Data Criada</th>
						<th>Título</th>
						<th>Descrição</th>
						<?php if ($_settings->userdata('type') == 1) : ?>
							<th>do utilizador</th>
						<?php endif; ?>
						<th>Status</th>
						<th>Açao</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$uwhere = "";
					if ($_settings->userdata('type') != 1) {
						$uwhere = " and a.user_id = '{$_settings->userdata('id')}' ";
					}
					$qry = $conn->query("SELECT a.*, u.username as writer from `article_list` a inner join users u on a.user_id = u.id where a.delete_flag = 0 {$uwhere} order by a.`title` asc ");
					while ($row = $qry->fetch_assoc()) :
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['title'] ?></td>
							<td>
								<p class="m-0 truncate-1"><?= $row['short_description'] ?></p>
							</td>
							<?php if ($_settings->userdata('type') == 1) : ?>
								<td><?php echo $row['writer'] ?></td>
							<?php endif; ?>
							<td class="text-center">
								<?php if ($row['status'] == 1) : ?>
									<span class="badge badge-primary px-3 rounded-pill">Publicadas</span>
								<?php else : ?>
									<span class="badge badge-secondary px-3 rounded-pill">Não-publicado</span>
								<?php endif; ?>
							</td>
							<td align="center">
								<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Açao
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item view_data" href="./?page=articles/view_article&id=<?= $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Visualizar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item edit_data" href="./?page=articles/manage_article&id=<?= $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Excluir</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("Are you sure to delete this article permanently?", "delete_article", [$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [{
				orderable: false,
				targets: [5, 6]
			}],
			order: [0, 'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})

	function delete_article($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_article",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("Um erro ocorreu.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("Um erro ocorreu.", 'error');
					end_loader();
				}
			}
		})
	}
</script>