<script type="text/javascript">
var addthis_config = {
     pubid: "ra-564475187f1feeaa"
};
</script>
<div class="container-fluid wrap">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h2>
							<?php _e( 'Cloaked Links', 'premium-link-cloaker-lite' ); ?>
							<a class="page-title-action" href="<?php echo menu_page_url( 'plcl_add_link', false ); ?>"><?php _e( 'Add New', 'premium-link-cloaker-lite' ); ?></a>
						</h2>
						<?php if ( isset( $_GET['action'] ) && 'search' == $_GET['action'] ) : ?>
							<a href="<?php echo menu_page_url( 'plcl', false ); ?>">&laquo; <?php _e( 'Back to main links page.', 'premium-link-cloaker-lite' ); ?></a>
						<?php endif; ?>
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
						<div class="col-md-2">
							<form id="plcl-links-form" method="POST" action="">
							<?php wp_nonce_field( 'bulk_action', 'plcl_nonce' ); ?>
								<div class="form-inline">
									<fieldset>
										<div class="form-group col-md-9 padding-md">
											<select class="form-control" name="bulk_action_1">
												<option><?php _e( 'Bulk Action', 'premium-link-cloaker-lite' ); ?></option>
												<option value="delete"><?php _e( 'Delete', 'premium-link-cloaker-lite' ); ?></option>
											</select>
										</div>
										<div class="form-group col-md-3 padding-0">
											<button class="btn btn-default" type="submit"><?php _e( 'Submit', 'premium-link-cloaker-lite' ); ?></button>
										</div>
									</fieldset>
								</div>
							</form>
						</div>
						<div class="col-md-2">
							<form method="GET" action="">
								<input type="hidden" name="page" value="plcl">
								<input type="hidden" name="action" value="search">
								<div class="form-inline">
									<fieldset>
										<div class="form-group col-md-9 padding-md">
											<select class="select2 form-control" name="cat">
												<option disabled="disabled" selected="selected"><?php _e( 'Category Filter', 'premium-link-cloaker-lite' ); ?></option>
												<?php foreach ( $cats as $cat ) : ?>
												<option value="<?php echo esc_attr( $cat['id'] ); ?>"><?php echo esc_attr( $cat['name'] ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="form-group col-md-3 padding-0">
											<button class="btn btn-default" type="submit"><?php _e( 'Filter', 'premium-link-cloaker-lite' ); ?></button>
										</div>
									</fieldset>
								</div>
							</form>
						</div>
						<div class="col-md-6"></div>
						<div class="col-md-2">
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
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="panel-body">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="col-cb"><input type="checkbox" class="plcl_link_cb plcl_link_cb_parent"></th>
											<th class="col-md-2"><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-3"><?php _e( 'Cloaked Link', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-4"><?php _e( 'Target URL', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-2"><?php _e( 'Share', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-1"><?php _e( 'Stats', 'premium-link-cloaker-lite' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php if ( ! empty( $links ) ) : ?>
										<?php foreach ( $links as $key => $link ) : ?>
										<?php $cloaked_url = str_replace( home_url( '/' ), '', esc_url ( $link['cloaked_url'] ) );  ?>
										<tr>
											<td><input type="checkbox" name="link_cb[]" class="plcl_link_cb" value="<?php echo esc_attr( $link['id'] ); ?>" form="plcl-links-form"></td>
											<td>
												<?php echo esc_attr( $link['name'] ); ?>
												<div class="plcl-row-actions">
													<span class="edit"><a href="<?php echo add_query_arg( array( 'link' => $link['id'], 'action' => 'edit' ), menu_page_url( 'plcl', false ) ); ?>"><?php _e( 'Edit', 'premium-link-cloaker-lite' ); ?></a> | </span>
													<span class="trash"><a href="<?php echo add_query_arg( array( 'link' => $link['id'], 'action' => 'delete', 'plcl_nonce' => wp_create_nonce( 'delete_link' . $link['id'] ) ), menu_page_url( 'plcl', false ) ); ?>"><?php _e( 'Delete', 'premium-link-cloaker-lite' ); ?></a></span>
												</div>
											</td>
											<td>
												<span class="cloaked_url"><?php echo home_url( '/' ) . esc_attr( $cloaked_url ); ?></span>
												<div class="plcl-row-actions">
													<span class="edit"><a href="#" class="copy-button" id="copy-button-<?php echo $key; ?>" data-clipboard-text="<?php echo home_url( '/' ) . esc_attr( $cloaked_url ); ?>"><?php _e( 'Copy Link', 'premium-link-cloaker-lite' ); ?></a></span>
												</div>
											</td>
											<td><?php echo esc_attr( $link['target_url'] ); ?></td>
											<td>
												<!-- <div class="addthis_native_toolbox" data-url="<?php echo home_url( '/' ) . esc_attr( $cloaked_url ); ?>" data-title="<?php echo esc_attr( $link['name'] ); ?>"></div> -->
												<p><?php _e( 'Pro Version Only', 'premium-link-cloaker-lite' ); ?></p>
											</td>
											<td><a href="<?php echo add_query_arg( array( 'link' => $link['id'], 'timeframe' => 7 ), menu_page_url( 'plcl_stats', false ) ); ?>"><?php _e( 'View', 'premium-link-cloaker-lite' ); ?></a></td>
										</tr>
										<?php endforeach; ?>
										<?php else : ?>
										<tr>
											<td colspan="6">
												<?php _e( 'No cloaked link available.', 'premium-link-cloaker-lite' ); ?>
											</td>
										</tr>
										<?php endif; ?>
					          		</tbody>
					          		<tfoot>
					          			<tr>
											<th class=""><input type="checkbox" class="plcl_link_cb plcl_link_cb_parent"></th>
											<th class=""><?php _e( 'Name', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Cloaked Link', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Target URL', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Share', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Stats', 'premium-link-cloaker-lite' ); ?></th>
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