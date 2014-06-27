<div class="well">
	<table class="table table-striped table_profile">

		<tr>
			<th style="width:30%"></th>
			<th style="width:70%"></th>
		</tr>
		<tr>
			<td>
				<b><?php echo CHtml::encode($model->getAttributeLabel('image')); ?></b>
			</td>
			<td>
				<?php 

					echo AvatarHelper::avatar(128, 128, "$model->avatar");
				 ?>
				
			</td>
		</tr>
		<tr>
			<td>
				<b><?php echo CHtml::encode($model->getAttributeLabel('name')); ?></b>
			</td>
			<td id="name">
				<?php echo CHtml::encode($model->name); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b><?php echo CHtml::encode($model->getAttributeLabel('second_name')); ?></b>
			</td>
			<td id="second_name">
				<?php echo CHtml::encode($model->second_name); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></b>
			</td>
			<td>
				<?php echo CHtml::encode($model->email); ?>
			</td>
		</tr>
		<?php 
			$attributes = array("country", "city", "age", "about");
			foreach ($attributes as $attr) {
				if ($model->$attr!==null && $model->$attr!=="") {
					$label = CHtml::encode($model->getAttributeLabel($attr));
					$value = nl2br(CHtml::encode($model->$attr));
					echo "<tr><td><b>$label</b></td>
					          <td id='$attr'>$value</td>
					          </tr>";
				}
			}
			if ($model->gender!==null) {
				$label = CHtml::encode($model->getAttributeLabel('gender'));
				$value = CHtml::encode($model->gender);
				$value = ($value==0) ? "мужской" : "женский";
				echo "<tr><td><b>$label</b></td>
				          <td id='gender'>$value</td></tr>";
			}

		 ?>
	</table>
</div>
