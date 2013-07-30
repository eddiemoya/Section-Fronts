		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $name; ?>"><?php echo $label; ?></label></th>
			<td>
				<?php print_pre($node); edit_post_link('Edit Layout', '', '', $layout_value); ?>
				<br />
				<span class="description"><?php _e($description); ?></span>
			</td>
		</tr>