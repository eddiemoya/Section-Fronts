		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $name; ?>"><?php echo $label; ?></label></th>
			<td>
				<select name="<?php echo $name;?>" id="<?php echo $name;?>">
					<option value='-1'>None</option>
					<option value='0'>Custom</option>
					<option value='-1'>--------</option>
					<?php foreach($templates as $template) : ?>

						<option value="<?php echo $template->ID; ?>">
							<?php echo $template->post_title; ?>
						</option>
					<?php endforeach; ?>
				</select>
				| <a href="blah">Edit</a>
				<br />
				<span class="description"><?php _e($description); ?></span>
			</td>
		</tr>