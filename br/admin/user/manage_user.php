<?php
require_once("./../../config.php");
if (isset($_GET['id'])) {
	$user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">
		<div class="form-group">
			<label for="name">Primeiro Nome</label>
			<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="name">Último Nome</label>
			<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Nome do usuário</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Senha</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" <?= isset($password) ? "" : "required" ?>>
			<?php if (isset($password)) : ?>
				<small><i>Deixe em branco se não quiser alterar a senha</i></small>
			<?php endif; ?>
		</div>
		<div class="form-group">
			<label for="type" class="control-label">Papel do usuário</label>
			<select name="type" id="type" class="form-control form-control-sm rounded-0" required>
				<option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Administrator</option>
				<option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Writer</option>
			</select>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Avatar</label>
			<div class="custom-file">
				<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
				<label class="custom-file-label" for="customFile">Examinar</label>
			</div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
		</div>
	</form>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		} else {
			$('#cimg').attr('src', "<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>");
		}
	}
	$('#manage-user').submit(function(e) {
		e.preventDefault();
		start_loader()
		$.ajax({
			url: _base_url_ + 'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					location.reload()
				} else {
					$('#msg').html('<div class="alert alert-danger">Nome de usuário existente</div>')
					end_loader()
				}
			}
		})
	})
</script>