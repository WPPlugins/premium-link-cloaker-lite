<?php $button = isset( $_GET['action'] ) && 'edit' == $_GET['action'] ? __( 'Edit Category', 'premium-link-cloaker-lite' ) : __( 'Add Category', 'premium-link-cloaker-lite' ); ?>
<h4 class="page-subtitle-header"><?php echo $button; ?></h4>
<form class="form-vertical" name="plcl_cat_form" action="" role="form" method="POST">
	<?php echo '<input type="hidden" name="action" value="' . $action . '">'; ?>
	<?php echo $nonce; ?>
	<?php echo isset( $_GET['cat'] ) ? '<input type="hidden" name="cat_id" value="' . $_GET['cat'] . '">' : '' ?>
	<div class="form-group">
		<label for="name" class="control-label"><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></label>
		<input name="name" type="text" class="form-control" id="name" value="<?php echo esc_attr( $value['name'] ); ?>">
		<p class="help-block help"><?php _e( 'Category name.', 'premium-link-cloaker-lite' ); ?></p>
		
	</div>
	<div class="form-group">
		<label for="description" class="control-label"><?php _e( 'Description', 'premium-link-cloaker-lite' ); ?></label>
		<textarea name="description" rows="4" class="form-control" id="description"><?php echo esc_textarea( $value['description'] ); ?></textarea>
		<p class="help-block help"><?php _e( 'Category description.', 'premium-link-cloaker-lite' ); ?></p>
	</div>
	<div class="form-group">
		<button class="btn btn-primary" name="submit" type="submit"><?php echo $button; ?></button>
	</div>
</form>