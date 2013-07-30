		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $label; ?>"><?php echo $label; ?></label></th>
			<td>
				<select name="<?php echo $name;?>" id="<?php echo $css_id;?>">
					<option value='<?php echo $default[1]; ?>'><?php echo $default[0]; ?></option>
					<option value='0'>--------</option>
					<?php foreach($templates as $template) : ?>

						<option value="<?php echo $template->ID; ?>" <?php echo ($template->ID == $layout_value) ? 'selected="selected"' : '';?>>
							<?php echo $template->post_title; ?>
						</option>
					<?php endforeach; ?>
				</select>
				| <?php edit_post_link('Edit Layout', '', '', $layout_value); ?>
				<br />
				<span class="description"><?php _e($description); ?></span>
			</td>
		</tr>