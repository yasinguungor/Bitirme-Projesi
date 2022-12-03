<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_document"><i class="fa fa-plus"></i> Yeni Ekle</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
			     <?php if($_SESSION['login_type'] == 1 ): ?>
				<colgroup>
					<col width="10%">
					<col width="25%">
					<col width="35%">
					<col width="20%">
					<col width="10%">
				</colgroup>
			    <?php else: ?>
				<colgroup>
					<col width="10%">
					<col width="30%">
					<col width="50%">
					<col width="10%">
				</colgroup>
			    <?php endif; ?>

				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Konu</th>
						<th>Açıklama</th>
					     <?php if($_SESSION['login_type'] == 1 ): ?>
						<th>Kullanıcı</th>
					    <?php endif; ?>
						<th>İşlem</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = '';
					if($_SESSION['login_type'] == 1 ):
					$user = $baglan->query("SELECT * FROM users where id in (SELECT user_id FROM documents) ");
					while($getir = $user->fetch_assoc()){
						$uname[$getir['id']] = ucwords($getir['lastname'].', '.$getir['firstname'].' ');
					}
					else:
						$where = " where user_id = '{$_SESSION['login_id']}' ";
					endif;
					$sorgu = $baglan->query("SELECT * FROM documents $where order by unix_timestamp(date_created) desc ");
					while($getir= $sorgu->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($getir['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($getir['title']) ?></b></td>
						<td><b class="truncate"><?php echo strip_tags($desc) ?></b></td>
						  <?php if($_SESSION['login_type'] == 1 ): ?>
						<td><?php echo isset($uname[$getir['user_id']]) ? $uname[$getir['user_id']] : "Kullanıcı Silindi" ?></td>
					    <?php endif; ?>
						<td class="text-center">
							
		                    <div class="btn-group">
		                        <a href="./index.php?page=edit_document&id=<?php echo $getir['id'] ?>" class="btn btn-primary btn-flat">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <a  href="./index.php?page=view_document&id=<?php echo md5($getir['id']) ?>" class="btn btn-info btn-flat">
		                          <i class="fas fa-eye"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_document" data-id="<?php echo $getir['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_document').click(function(){
	_conf("Dosyayı silmek istediğinden emin misin?","delete_document",[$(this).attr('data-id')])
	})
	})
	function delete_document($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_file',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dosya başarıyla silindi",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>