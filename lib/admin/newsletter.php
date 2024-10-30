<div class="wrap">
	<div class="icon32" id="icon-mandoo"></div>
	<h2><?php echo __('Newsletter');?></h2>
	<?php 
	$doAPI = $this->get_doAPI();
	if (false != $doAPI) {
	
		if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
		else $tab = 'template';

		echo self::ilc_admin_tabs($tab); 
		?>
		<div>
		<form action="options.php" method="post">
		<?php settings_fields($_GET['page'] . '_options');
		switch ($tab) {
			case "template":
				echo '<table class="form-table">';
					echo '<tr>';
						echo '<td>';
						echo '<h3>' . __('Header','mandoo') . '</h3>';
						wp_editor( $this->options['header'], 'newsletter_options[header]' ); 
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
						echo '<h3>' . __('Background color','mandoo') . '</h3>';
						echo '<input type="text" id="bg-color" name="newsletter_options[bg-color]" value="' . $this->options['bg-color'] . '" data-default-color="#effeff" />';
						echo '<h3>' . __('E-mail Background color','mandoo') . '</h3>';
						echo '<input type="text" id="mail-bg-color" name="newsletter_options[mail-bg-color]" value="' . $this->options['mail-bg-color'] . '" data-default-color="#effeff" />';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
						echo '<h3>' . __('Footer','mandoo') . '</h3>';
						wp_editor( $this->options['footer'], 'newsletter_options[footer]' ); 
						echo '</td>';
					echo '</tr>';
				echo '</table>';
				echo '<p class="submit">';
					echo '<input type="submit" name="Submit" class="button-primary" value="' . __('Save Changes','mandoo') . '" /> ';
					echo '<input type="hidden" name="preview-type" id="preview-type" value="0" />';
					echo '<input type="button" name="newsletter-preview" id="newsletter-preview" class="button-primary" value="' . __('Preview Mail','mandoo') . '" />';
				echo '</p>';
				break;
			case "send":
				echo '<table class="form-table">';
					echo '<tr>';
						echo '<td>';
							echo '<h3>' . __('General options','mandoo') . '</h3>';
							echo '<table>';
								echo '<tr>';
									echo '<td>';
										echo '<p>';
											echo __('Subject','mandoo');
										echo '</p>';
									echo '</td>';
									echo '<td>';
										echo '<input type="text" name="subject" id="subject" class="regular-text" />';
									echo '</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<td>';
										echo '<p>';
											echo __('Select Mandoo doMail campaign','mandoo');
										echo '</p>';
									echo '</td>';
									echo '<td>';
									$campaigns = $doAPI->doMail_GetCampaign(0);
								
									$result = explode("|",$campaigns);
			
									if ($result[0] != "E:501") {
										$campaigns = new SimpleXMLElement($campaigns);
										?>
										<select name="campaign" id="campaign">
											<option value="0"><?php echo __('Select','mandoo'); ?></option>
											<?php	
											foreach ($campaigns->result->node as $node) {
												$selected = '';
												if (intval($this->options["campaign"]) == intval($node->id)) $selected = 'selected="selected"';
												echo '<option value="' . $node->id . '" ' . $selected . '>' . $node->users_username . '</option>';
											}
											?>
										</select>
									<?php
									}
									else {
										$this->admin_bad_api_keys ();
									}
									echo '</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<td>';
										echo __('Publish on RSS','mandoo');
									echo '</td>';
									echo '<td>';
										echo '<input type="checkbox" value="1" name="publishOnRss" id="publishOnRss" />';
									echo '</td>';
								echo '</tr>';
							echo '</table>';
							//echo '<input type="checkbox" value="1" name="googleAnalyticsId" /> ' . __('Google Analytics ID');
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
						echo '<h3>' . __('Select a','mandoo') .  ' <a href="http://www.mandoocms.com/es_ES/features/1247/" target="_blank" title="Mandoo™ doMails">Mandoo™ doMails</a> ' .  __('group to send the newsletter.','mandoo') . '</h3>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
							$groups = $doAPI->doMail_Groups();
							
							$result = explode("|",$groups);
			
							if ($result[0] != "E:501") {
								$groups = new SimpleXMLElement($groups);
									// $data = array();
									// foreach ($groups->node as $node) {
										// $num_subscribers = 0;
										// if (intval($node->type) == 1) {
											// $num_subscribers = $doAPI->doMail_GetNumSubscribersFromSmartGroups($node->id);
										// }
										// else {
											// $num_subscribers = $doAPI->doMail_GetNumSubscribersFromSimpleGroup($node->id);
										// }
										// $data[] = array('name' => $node->title, 
														// 'num_subscriber' => $num_subscribers
													// );
									// }
									// $list_table = new Newsletters_Table($data);
									// $list_table->prepare_items();
									// $list_table->display();
								echo '<ul class="list-group">';
									foreach ($groups->node as $node) { 
										$title = $node->title;
										if ($title == "") {
											$title = __("No name");
										}
										echo '<li><input type="checkbox" value="' . $node->id . '" name="group[]" class="group" /> ' . $title . ' <b>(' . $node->numsubscribers . ')</b></li>';
									}
								echo '</ul>';
							}
							else {
								$this->admin_bad_api_keys();
							}
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
						echo '<h3>' . __('Select your newsletter content.','mandoo') . '</h3>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
							echo '<ul>';
							$output = get_posts();
							foreach ($output as $post) {
								echo '<li><input type="checkbox" name="post[]" value="' . $post->ID . '" class="post"/> ' . $post->post_title . '</li>';
							}
							echo '</ul>';
						echo '</td>';
					echo '</tr>';
					
				echo '</table>';
				echo '<p class="submit">';
					echo '<span class="spinner"></span>';
					echo '<input type="button" name="confirm-send-mail" id="confirm-send-mail" class="button-primary" value="' .  __('Send Mail','mandoo') . '" /> ';
					echo '<input type="hidden" name="preview-type" id="preview-type" value="1" />';
					echo '<input type="button" name="newsletter-preview" id="newsletter-preview" class="button-primary" value="' . __('Preview Mail','mandoo') . '" />';
				echo '</p>';
				break;
		}
	}
	?>
	</div>
	<div id="newsletter-preview-wrapper">
	<?php echo '<a href="javascript:void(null);" id="newsletter-preview-close" class="media-modal-close" title="' . __('Close','mandoo') . '"><span class="media-modal-icon"></span></a>'; ?>
		<div id="newsletter-preview-content"></div>
	</div>
	<div id="newsletter-confirm-wrapper">
	</div>
</div>