<div class="span10">
<script>
function ping(host_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/manage/pingnode/'+host_id, function(json){
		if(json.status == "TRUE")
		{
			$('#ping_node_'+host_id).addClass('btn-success');
			html = '<i class=icon-arrow-up></i><?php echo $common_online;?>';
		}
		else
		{
			$('#ping_node_'+host_id).removeClass('btn-success');
			$('#ping_node_'+host_id).addClass('btn-warning');
			html = '<i class=icon-arrow-down></i><?php echo $common_offline;?>';
		}
		$('#ping_node_'+host_id).html(html);
	});
}
</script>
	<!--<div class="alert alert-error"><?php echo $common_add_node_tips?></div>-->
	<table class="table table-striped table_hover">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo $common_hostname;?></th>
				<th><?php echo $common_ip_addr;?></th>
				<th><?php echo $common_node_role;?></th>
				<th><?php echo $common_create_time;?></th>
				<th><?php echo $common_action;?></th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1;foreach($results as $item):?>
			<tr>
				<td><?php echo $i?></td>
				<td><?php echo $item->hostname;?></td>
				<td><?php echo $item->ip;?></td>
				<td><?php
				if (preg_match("/namenode/i",$item->role) || preg_match("/secondarynamenode/i",$item->role) || preg_match("/jobtracker/i",$item->role)):
					echo $item->role;
				else:
					echo $item->role;
				endif;
				?>
				</td>
				<td><?php echo $item->create_time;?></td>
				<td>
				<div class="btn-group">
					<button class="btn" id="ping_node_<?php echo $item->host_id;?>"><i class=icon-refresh></i><?php echo $common_ping_node;?></button>
					<script>
					
					ping(<?php echo $item->host_id;?>);
					setInterval(function()
					{
						ping(<?php echo $item->host_id;?>)
					},5000);
					</script>
					<a class="btn" href="#update_hadoop_node_<?php echo $item->host_id;?>" data-toggle="modal"><i class=icon-wrench></i><?php echo $common_modify_node;?></a>
					<a class="btn btn-danger" href="#delete_hadoop_node_<?php echo $item->host_id;?>" data-toggle="modal"><i class=icon-remove></i><?php echo $common_remove_node;?></a>
				</div>
				
<!--update modal-->
<div id="update_hadoop_node_<?php echo $item->host_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/manage/updatehadoopnode/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">修改节点</h3>
	</div>
	<div class="modal-body">
		<table>
			<tr>
				<td>
				<label><?php echo $common_hostname;?></label><input type="text" placeholder="<?php echo $common_hostname;?>" name="hostname" value="<?php echo $item->hostname;?>" /><br />
				</td>
				<td>
				<label><?php echo $common_ip_addr;?></label><input type="text" placeholder="<?php echo $common_ip_addr;?>" name="ipaddr" value="<?php echo $item->ip;?>" /><br />
				</td>
			</tr>
			<tr>
				<td><label><?php echo "ROOT 用户名(选填)";?></label><input type="text" name="ssh_user" placeholder="ssh_user" value="root" disabled />
				</td>
				<td><label><?php echo "ROOT 密  码(选填)";?></label><input type="text" name="ssh_pass" placeholder="ssh_pass" value="<?php echo $item->ssh_pass;?>" />
				</td>
			</tr>
			<tr>
				<td>
				<label><?php echo $common_role_name;?></label>
				</td>
				<td>
					<label><?php echo "机架位置(选填)";?></label>
				</td>
			</tr>
			<tr>
				<td>
					<?php
					$tmp = explode(",", $item->role);
					?>
					<input type="checkbox"  name="role[]" value="namenode" <?php foreach($tmp as $v): echo ($v == "namenode") ? "checked" : ""; endforeach;?> />Namenode<br />
					<input type="checkbox"  name="role[]" value="datanode" <?php foreach($tmp as $v): echo ($v == "datanode") ? "checked" : ""; endforeach;?> />Datanode<br />
					<input type="checkbox"  name="role[]" value="secondarynamenode" <?php foreach($tmp as $v): echo ($v == "secondarynamenode") ? "checked" : ""; endforeach;?> />SecondaryNamenode<br />
					<input type="checkbox"  name="role[]" value="jobtracker" <?php foreach($tmp as $v): echo ($v == "jobtracker") ? "checked" : ""; endforeach;?> />Jobtracker<br />
					<input type="checkbox"  name="role[]" value="tasktracker" <?php foreach($tmp as $v): echo ($v == "tasktracker") ? "checked" : ""; endforeach;?> />Tasktracker<br />
				</td>
				<td>
					<input type="text" name="rack" placeholder="Rack number" value="<?php echo $item->rack;?>" /><br />
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="host_id" value="<?php echo $item->host_id;?>" />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
</form>
</div>
<!--update modal end-->

<!--delete modal-->
<div id="delete_hadoop_node_<?php echo $item->host_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form action="<?php echo $this->config->base_url();?>index.php/manage/deletehadoopnode/" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">删除节点</h3>
	</div>
	<div class="modal-body">
			<label><?php echo $common_hostname;?> : <?php echo $item->hostname;?></label><br />
			<label><?php echo $common_ip_addr;?> : <?php echo $item->ip;?></label><br />
			<pre class="alert alert-error"><h4><?php echo $common_remove_node_tips;?></h4></pre>
			<input type="hidden" name="host_id" value="<?php echo $item->host_id;?>" />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-danger"><?php echo $common_submit;?></button>
	</div>
</form>
</div>
<!--delete modal end-->
				
				</td>
			</tr>
		<?php $i++; endforeach;?>
		</tbody>
	</table>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>