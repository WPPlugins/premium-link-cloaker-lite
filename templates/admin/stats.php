<?php $timeframe = isset( $_GET['timeframe'] ) && isset( $_GET['link'] ) ? (int) $_GET['timeframe'] : 7 ?>
<?php $link      = isset( $_GET['link'] ) && is_numeric( $_GET['link'] ) && isset( $_GET['timeframe'] ) ? premium_link_cloaker_lite()->link->get_link_name( $_GET['link'] ) : __( 'All', 'premium-link-cloaker-lite' ); ?>
<div class="container-fluid wrap">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h2><?php _e( 'Stats', 'premium-link-cloaker-lite' ); ?></h2>
						<span><?php printf( __( 'Link: %1$s. Last %2$d day(s) data.', 'premium-link-cloaker-lite' ), $link, $timeframe ); ?></span>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="plcl-chart-wrapper">
								<div id="plcl-chart">
									<h2 class="text-center stats-pro-text"><?php _e( 'Click Stats Graphic Available in Pro Version', 'premium-link-cloaker-lite' ); ?></h2>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-inline">
								<form method="GET" action="">
									<input type="hidden" name="page" value="plcl_stats">
									<fieldset>
										<div class="form-group col-md-4 padding-md">
											<select class="form-control select2" id="plcl_stats_filter_link" name="link">
												<option disabled="disabled" selected="selected" hidden="hidden"><?php _e( 'Select Link', 'premium-link-cloaker-lite' ); ?></option>
												<option value="all"><?php _e( 'All Links', 'premium-link-cloaker-lite' ); ?></option>
												<?php foreach ( $links as $link ) : ?>
												<option value="<?php echo $link['id'] ?>"><?php echo $link['name'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="form-group col-md-3 padding-md">
											<select class="form-control" name="timeframe">
												<option disabled="disabled" selected="selected" hidden="hidden"><?php _e( 'Time Frame', 'premium-link-cloaker-lite' ); ?></option>
												<option value="1"><?php _e( 'Last Day', 'premium-link-cloaker-lite' ); ?></option>
												<option value="7"><?php _e( 'Last Week', 'premium-link-cloaker-lite' ); ?></option>
												<option value="14"><?php _e( 'Last 2 Weeks', 'premium-link-cloaker-lite' ); ?></option>
												<option value="30"><?php _e( 'Last 30 Days', 'premium-link-cloaker-lite' ); ?></option>
											</select>
										</div>
										<div class="form-group col-md-2 padding-0">
											<button class="btn btn-default" type="submit"><?php _e( 'Filter', 'premium-link-cloaker-lite' ); ?></button>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
						<div class="col-md-6">
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
											<th class="col-md-3"><?php _e( 'URL', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-4"><?php _e( 'Referrer', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-2"><?php _e( 'IP', 'premium-link-cloaker-lite' ); ?></th>
											<th class="col-md-3"><?php _e( 'Date', 'premium-link-cloaker-lite' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php if ( ! empty( $clicks ) ) : ?>
										<?php foreach ( $clicks as $key => $click ) : ?>
										<tr>
											<td><?php echo esc_url( $click['url'] ); ?></td>
											<td>
												<?php if ( ! empty( $click['referrer'] ) ) : ?>
												<?php echo esc_url( $click['referrer'] ); ?>
												<?php else : ?>
												<?php _e( 'Direct', 'premium-link-cloaker-lite' ); ?>
												<?php endif; ?>
											</td>
											<td><?php echo esc_attr( $click['ip'] ); ?></td>
											<td><?php echo date( 'M j, Y H:i:s', strtotime( esc_attr( $click['date'] ) ) ); ?></td>
										</tr>
										<?php endforeach; ?>
										<?php else : ?>
										<tr>
											<td colspan="6">
												<?php _e( 'No stats available yet.', 'premium-link-cloaker-lite' ); ?>
											</td>
										</tr>
										<?php endif; ?>
					          		</tbody>
					          		<tfoot>
					          			<tr>
											<th class=""><?php _e( 'URL', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Referrer', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'IP', 'premium-link-cloaker-lite' ); ?></th>
											<th class=""><?php _e( 'Date', 'premium-link-cloaker-lite' ); ?></th>
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