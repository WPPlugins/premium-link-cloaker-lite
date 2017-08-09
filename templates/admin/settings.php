<?php $post_types = premium_link_cloaker_lite()->settings->get_post_types(); ?>
<div class="container-fluid wrap">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h2><?php _e( 'Settings', 'premium-link-cloaker-lite' ); ?></h2>
					</div>
				</div>
				<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
				<?php $status = strpos( $_GET['settings-updated'], 'true' ) !== false ? 'updated' : 'error'; ?>
				<div class="row">
					<div class="col-md-12">
						<div class="<?php echo $status; ?>">
							<p class="">
								<?php if ( 'updated' == $status ) : ?>
								<?php _e( 'Settings saved.', 'premium-link-cloaker-lite' ); ?>
								<?php else : ?>
								<?php _e( 'Failed to save settings', 'premium-link-cloaker-lite' ); ?>
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-md-12">
						<div class="panel-body">
							<form class="form-horizontal" name="plcl_form" action="options.php" role="form" method="POST">
							<?php settings_fields( 'plcl_settings_group' ); ?>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label"><?php _e( 'Keyword Linking (Pro Version)', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-10">
										<hr>
									</div>
								</div>
								<div class="form-group">
									<label for="kw_linking_enable" class="col-sm-2 control-label"><?php _e( 'Enable', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-4">
										<div class="checkbox">
											<label>
												<input name="" type="checkbox" class="form-control" id="kw_linking_enable" value="1" disabled="disabled">
											</label>
										</div>
										<p class="help-block help"><?php _e( 'Check this box to enable automatic keyword linking feature.', 'premium-link-cloaker-lite' ); ?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label"><?php _e( 'Post Types', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-4">
										<?php foreach ( $post_types as $post_type ) : ?>
										<div class="checkbox">
											<label for="kw_linking_display_<?php echo $post_type->name; ?>">
												<input name="" type="checkbox" class="form-control" id="kw_linking_display_<?php echo $post_type->name; ?>" value="" disabled="disabled">
												<?php echo $post_type->labels->name; ?>
											</label>
										</div>
										<?php endforeach; ?>
										<p class="help-block help"><?php _e( 'Post types that the feature will be applied to.', 'premium-link-cloaker-lite' ); ?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label"><?php _e( 'Frequency', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-4">
										<select name="" class="form-control">
											<option disabled="disabled"><?php _e( 'Low', 'premium-link-cloaker-lite' ); ?> (10%)</option>
											<option disabled="disabled"><?php _e( 'Medium', 'premium-link-cloaker-lite' ); ?> (30%)</option>
											<option disabled="disabled"><?php _e( 'High', 'premium-link-cloaker-lite' ); ?> (50%)</option>
										</select>
										<p class="help-block help"><?php _e( 'How frequent keywords will be linked to cloaked links.', 'premium-link-cloaker-lite' ); ?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label"><?php _e( 'Misc', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-10">
										<hr>
									</div>
								</div>
								<div class="form-group">
									<label for="misc_delete_on_uninstall" class="col-sm-2 control-label"><?php _e( 'Delete Plugin Data', 'premium-link-cloaker-lite' ); ?></label>
									<div class="col-sm-4">
										<div class="checkbox">
											<label>
												<input name="plcl_settings[misc_delete_on_uninstall]" type="checkbox" class="form-control" id="misc_delete_on_uninstall" value="1" <?php checked( $plcl_settings['misc_delete_on_uninstall'], 1, true ); ?>>
											</label>
										</div>
										<p class="help-block help"><?php _e( 'Check this box to delete all plugin data when it\'s uninstalled.', 'premium-link-cloaker-lite' ); ?></p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-2"></div>
									<div class="col-sm-4">
										<button class="btn btn-primary" name="plcl_settings_submit" type="submit"><?php _e( 'Save Settings', 'premium-link-cloaker-lite' ); ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php include 'sidebar.php'; ?>
		</div>
	</div>
</div>