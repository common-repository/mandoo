<?php
global $blog_id;	
$user_subscribe_group = (is_array($this->options)) ? $this->options['group'] : '';
if (isset($_POST[$_GET['page'] . '_nonce'])) {
	$nonce = $_POST[$_GET['page'] . '_nonce'];
	$nonce_key = $_GET['page'] . '_update_options';
	if (! wp_verify_nonce($nonce, $nonce_key)) {
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div>
			<h2>Mandoo User Subscribe Settings</h2>
			<p><?php echo __('What you\'re trying to do looks a little shady.','mandoo'); ?></p>
		</div>
		<?php
		return false;
	} else {
		$new_user_subscribe_group = $_POST[$_GET['page'] . '-group'];
		$new_options['group'] = $new_user_subscribe_group;
		$this->update_options($new_options);
		$user_subscribe_group = $this->options['group'];
	}
}
?>
<div class="wrap">
	<div class="icon32" id="icon-mandoo"></div>
	<h2><?php echo __('User Subscribe Settings','mandoo') ; ?></h2>
	<?php
	$doAPI = $this->get_doAPI();
	if (false != $doAPI) {
	
		$groups = $doAPI->doMail_Groups();
		
		$result = explode("|",$groups);
			
		if ($result[0] != "E:501") {
			$groups = new SimpleXMLElement($groups);
			
			$num = 0;
			$html = '';
			foreach ($groups->node as $node) {
				if ($node->type == 0) {
					$selected = ($this->options['group'] == $node->id) ? ' selected="selected" ' : '';
					$html .= '<option ' . $selected . ' value="' . $node->id . '"> ' . $node->title . '</option>';
					$num++;
				}
			}
			if ($num > 0) {
				?>
				<form action="options.php" method="post">
					<?php settings_fields($_GET['page'] . '_options'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
									<label for="<?php echo $_GET['page']; ?>-group"><?php echo __('Select a group: ', 'mandoo'); ?></label>
							</th>
							<td>
								<?php
								?>
									<select id="<?php echo $_GET['page']; ?>-group" name="<?php echo $_GET['page']; ?>_options[group]">
										<option value="0"><?php echo __('Select','mandoo') ?></option> 
										<?php	
										echo $html;
										?>
									</select>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php echo  __('Save Changes','mandoo'); ?>" />
					</p>
				</form>
			<?php
			}
			else { 
			$this->admin_notices_no_group();
			?>
				<form action="admin.php?page=<?php echo $_GET['page']; ?>" method="post">
					<?php settings_fields($_GET['page'] . '_options'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label for="<?php echo $_GET['page']; ?>-group"><?php echo __('Group name: ', 'mandoo'); ?></label>
							</th>
							<td>
								  <input type="hidden" name="action" value="mandoo_create_group" />
								  <input type="text" class="regular-text" id="<?php echo $_GET['page']; ?>-new-group" name="<?php echo $_GET['page']; ?>-new-group" value="" />
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php echo  __('Create group','mandoo'); ?>" />
					</p>
				</form>
			
			<?php	
			}
			?>
			
	<?php
		}
		else {
			$this->admin_bad_api_keys ();
		}
	}
	?>
</div>