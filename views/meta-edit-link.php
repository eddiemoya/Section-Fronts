		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $name; ?>"><?php echo $label; ?></label></th>
			<td>
				<?php edit_post_link('Edit Layout', '', '', $node->post->ID); ?>
				<br />
				<span class="description"><?php _e($description); ?></span>
			</td>
		</tr>