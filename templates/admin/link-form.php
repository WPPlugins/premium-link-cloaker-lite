<?php $button = isset( $_GET['action'] ) && 'edit' == $_GET['action'] ? __( 'Edit Link', 'premium-link-cloaker-lite' ) : __( 'Add New Link', 'premium-link-cloaker-lite' ); ?>
<?php $cloaked_url = str_replace( home_url( '/' ), '', esc_url ( $value['cloaked_url'] ) );  ?>
<form class="form-horizontal" name="plcl_form" action="" role="form" method="POST">
	<?php echo '<input type="hidden" name="action" value="' . $action . '">'; ?>
	<?php echo $nonce; ?>
	<?php echo isset( $_GET['link'] ) ? '<input type="hidden" name="link_id" value="' . $_GET['link'] . '">' : '' ?>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label"><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<input name="name" type="text" class="form-control" id="name" value="<?php echo esc_attr( $value['name'] ); ?>">
			<p class="help-block help"><?php _e( 'Name for link identification.', 'premium-link-cloaker-lite' ); ?></p>
		</div>
	</div>
	<div class="form-group">
		<label for="target-url" class="col-sm-2 control-label"><?php _e( 'Target URL', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<input name="target_url" type="text" class="form-control" id="target-url" value="<?php echo esc_url( $value['target_url'] ); ?>">
			<p class="help-block help"><?php _e( 'URL you want to cloak.', 'premium-link-cloaker-lite' ); ?></p>
		</div>
	</div>
	<div class="form-group">
		<label for="cloaking-type" class="col-sm-2 control-label"><?php _e( 'Cloaking Type', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<select name="cloaking_type" class="form-control" id="cloaking-type">
				<option value="redirect" <?php selected( $value['cloaking_type'], 'redirect', true ); ?>><?php _e( 'Redirect', 'premium-link-cloaker-lite' ); ?></option>
				<option disabled="disabled"><?php _e( 'Mask (Pro Version)', 'premium-link-cloaker-lite' ); ?></option>
			</select>
			<p class="help-block help"><?php _e( 'Cloaking type.', 'premium-link-cloaker-lite' ); ?></p>
		</div>
	</div>
	<div class="form-group">
		<label for="cloaked-url" class="col-sm-2 control-label"><?php _e( 'Cloaked URL', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><?php echo home_url( '/' ); ?></span>
				<input name="cloaked_url" type="text" class="form-control" id="cloaked-url" value="<?php echo esc_attr( $cloaked_url ); ?>">
			</div>
			<p class="help-block help"><?php _e( 'URL you want your target URL is cloaked with.', 'premium-link-cloaker-lite' ); ?></p>
		</div>
	</div>
	<div class="form-group">
		<label for="keywords" class="col-sm-2 control-label"><?php _e( 'Categories', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<?php if ( ! empty( $cats ) ) : ?>
			<?php $num  = count( $cats );  ?>
			<?php $half = ceil( $num / 2 );  ?>
			<div class="row">
				<div class="col-sm-6">
					<?php for ( $i = 0; $i < $half; $i++ ) : ?>
					<div class="checkbox">
						<label for="cat-<?php echo esc_attr( $cats[$i]['id'] ); ?>">
							<input type="checkbox" name="categories[]" id="cat-<?php echo esc_attr( $cats[$i]['id'] ); ?>" value="<?php echo esc_attr( $cats[$i]['id'] ); ?>" <?php echo in_array( $cats[$i]['id'], $l_cats ) ? 'checked="checked"' : ''; ?>>
							<?php echo esc_attr( $cats[$i]['name'] ); ?>
						</label>
					</div>
					<?php endfor; ?>
				</div>
				<div class="col-sm-6">
					<?php for ( $i = $half; $i < $num; $i++ ) : ?>
					<div class="checkbox">
						<label for="cat-<?php echo esc_attr( $cats[$i]['id'] ); ?>">
							<input type="checkbox" name="categories[]" id="cat-<?php echo esc_attr( $cats[$i]['id'] ); ?>" value="<?php echo esc_attr( $cats[$i]['id'] ); ?>" <?php echo in_array( $cats[$i]['id'], $l_cats ) ? 'checked="checked"' : ''; ?>>
							<?php echo esc_attr( $cats[$i]['name'] ); ?>
						</label>
					</div>
					<?php endfor; ?>
				</div>
			</div>
			<p class="help-block help"><?php printf( __( 'Categories the link belongs to. <a href="%s">Click here</a> to create new category.', 'premium-link-cloaker-lite' ), menu_page_url( 'plcl_categories', false ) ); ?></p>
			<?php else: ?>
			<p class="help-block help"><?php printf( __( 'No category available. <a href="%s">Click here</a> to create new category.', 'premium-link-cloaker-lite' ), menu_page_url( 'plcl_categories', false ) ); ?></p>
			<?php endif ?>
		</div>
	</div>
	<div class="form-group">
		<label for="keywords" class="col-sm-2 control-label"><?php _e( 'Keywords', 'premium-link-cloaker-lite' ); ?></label>
		<div class="col-sm-6">
			<textarea class="form-control" id="keywords" rows="4" disabled="disabled"><?php _e( '(Pro Version)', 'premium-link-cloaker-lite' ); ?></textarea>
			<p class="help-block help"><?php _e( 'Case sensitive. Keywords you want to be automatically linked to the cloaked URL, separated by comma.', 'premium-link-cloaker-lite' ); ?></p>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2"></div>
		<div class="col-sm-6">
			<button class="btn btn-primary" name="submit" type="submit"><?php echo $button; ?></button>
		</div>
	</div>
</form>