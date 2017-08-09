<div class="container-fluid wrap">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h2>
							<?php _e( 'Categories', 'premium-link-cloaker-lite' ); ?>
							<a class="page-title-action" href="<?php echo menu_page_url( 'plcl_categories', false ); ?>"><?php _e( 'Add New', 'premium-link-cloaker-lite' ); ?></a>
						</h2>
					</div>
				</div>
				<?php if ( isset( $_GET['status'] ) ) : ?>
				<?php $class = strpos( $_GET['status'], 'success' ) !== false ? 'updated' : 'error'; ?>
				<div class="row">
					<div class="col-md-12">
						<div class="<?php echo $class; ?> plcl-status">
							<p class="">
								<?php echo $messages[ $_GET['status'] ]; ?>
							</p>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<?php include 'cat-form.php'; ?>
						</div>
						<div class="col-md-8">
							<div class="col-md-4 padding-0">
								<form id="plcl-category" method="POST" role="form" action="">
									<?php wp_nonce_field( 'cat_bulk_action', 'plcl_nonce' ); ?>
									<div class="row">
										<div class="col-md-10">
											<div class="form-inline">
												<fieldset>
													<div class="form-group col-md-10 padding-md">
														<select class="form-control" name="cat_bulk_action_1">
															<option><?php _e( 'Bulk Action', 'premium-link-cloaker-lite' ); ?></option>
															<option value="delete_cat"><?php _e( 'Delete', 'premium-link-cloaker-lite' ); ?></option>
														</select>
													</div>
													<div class="form-group col-md-2 padding-0">
														<button class="btn btn-default" name="submit_1" type="submit"><?php _e( 'Submit', 'premium-link-cloaker-lite' ); ?></button>
													</div>
												</fieldset>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="col-md-8 padding-0">
								<nav>
									<ul class="pagination">
										<?php $number_of_pages = ceil( $count / 10 ) ; ?>
										<?php $page = isset( $_GET['paged'] ) ? $_GET['paged'] : 1; ?>
										<?php $disabled = ( isset( $_GET['paged'] ) && 1 == $_GET['paged'] ) || ( ! isset( $_GET['paged'] ) ) ? 'disabled' : ''; ?>
										<?php $url = 'disabled' == $disabled ? '#' : add_query_arg( array( 'paged' => $_GET['paged'] - 1 ), preg_replace( array( '/paged=\d+/', '/(status=.*)&?/' ), '', esc_url_raw( $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ); ?>
										<li class="<?php echo $disabled; ?>">
											<a href="<?php echo $url; ?>" aria-label="Previous">
												<span aria-hidden="true">&laquo;</span>
											</a>
										</li>
										<?php for ( $i = 1; $i <= $number_of_pages; $i++ ) : ?>
										<?php $active = ( isset( $_GET['paged'] ) && $i == $_GET['paged'] ) || ( ! isset( $_GET['paged'] ) && 1 == $i ) ? 'active' : '';  ?>
										<?php if ( 'active' != $active && ( $i > $page + 2 || $i < $page - 2 ) ) continue; ?>
										<li class="<?php echo $active; ?>"><a href="<?php echo add_query_arg( array( 'paged' => $i ), preg_replace( array( '/paged=\d+/', '/(status=.*)&?/' ), '', esc_url_raw( $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ); ?>"><?php echo $i; ?></a></li>
										<?php endfor; ?>
										<?php $disabled = ( isset( $_GET['paged'] ) && $number_of_pages == $_GET['paged'] ) || ( ! isset( $_GET['paged'] ) && 1 >= $number_of_pages ) ? 'disabled' : ''; ?>
										<?php $url = 'disabled' == $disabled ? '#' : add_query_arg( array( 'paged' => ( isset( $_GET['paged'] ) ? $_GET['paged'] : 1 ) + 1 ), preg_replace( array( '/paged=\d+/', '/(status=.*)&?/' ), '', esc_url_raw( $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) ) ); ?>
										<li class="<?php echo $disabled; ?>">
											<a href="<?php echo $url; ?>" aria-label="Next">
												<span aria-hidden="true">&raquo;</span>
											</a>
										</li>
									</ul>
								</nav>
							</div>
							<div class="panel-body">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="col-cb"><input type="checkbox" class="plcl_cat_cb_parent plcl_cat_cb"></th>
											<th class="col-md-4"><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-8"><?php _e( 'Description', 'premium-link-cloaker-lite' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php if ( ! empty( $cats ) ) : ?>
										<?php foreach ( $cats as $cat ) : ?>
										<tr>
											<td><input type="checkbox" name="cat_cb[]" class="plcl_cat_cb" value="<?php echo esc_attr( $cat['id'] ); ?>" form="plcl-category"></td>
											<td>
												<?php echo esc_attr( $cat['name'] ); ?>
												<div class="plcl-row-actions">
													<span class="edit"><a href="<?php echo add_query_arg( array( 'cat' => $cat['id'], 'action' => 'edit' ), menu_page_url( 'plcl_categories', false ) ); ?>"><?php _e( 'Edit', 'premium-link-cloaker-lite' ); ?></a> | </span>
													<span class="trash"><a href="<?php echo add_query_arg( array( 'cat' => $cat['id'], 'action' => 'delete_cat', 'plcl_nonce' => wp_create_nonce( 'delete_cat' . $cat['id'] ) ), menu_page_url( 'plcl_categories', false ) ); ?>"><?php _e( 'Delete', 'premium-link-cloaker-lite' ); ?></a></span>
												</div>
											</td>
											<td><?php echo esc_attr( $cat['description'] ); ?></td>
										</tr>
										<?php endforeach; ?>
										<?php else: ?>
										<tr>
											<td colspan="4">
												<?php _e( 'No category available.', 'premium-link-cloaker-lite' ); ?>
											</td>
										</tr>
										<?php endif; ?>
					          		</tbody>
					          		<tfoot>
										<tr>
											<th><input type="checkbox" class="plcl_cat_cb_parent plcl_cat_cb"></th>
											<th><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></th>
											<th><?php _e( 'Description', 'premium-link-cloaker-lite' ); ?></th>
										</tr>
									</tfoot>
					        	</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include 'sidebar.php'; ?>
		</div>
	</div>
</div>