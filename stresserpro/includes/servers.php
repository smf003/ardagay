<?php
include 'configuration.php'; 
include 'extra.php';
?>
 <div class="table-responsive-lg">
                                        <table class="table ">
                                            <thead class="">
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Usage</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Status</th>

                                                </tr>
                                            </thead>
                                            <tbody>	
	
<?php

		$SQLGetInfo = $odb->query("SELECT * FROM `serveurs` ORDER BY `id` DESC");
	while ($getInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC)) {
		$name = $getInfo['name'];
		$ID = $getInfo['ID'];
		$type = $getInfo['type'];
		if($type == 4){ $network = '<span class="badge badge-soft-success">Layer 4</span>'; }
		if($type == 7){ $network = '<span class="badge badge-soft-success">Layer 7</span>'; }
		if($getInfo['status'] == 1){ $status = '<i class="bx mr-2 bx-x text-danger"></i> Offline'; }
        if($getInfo['status'] == 2){ $status = '<i class="fa fa-spinner fa-spin"></i> Maintenance'; }
        if($getInfo['status'] == 0){ $status = '<i class="mdi mdi-checkbox-marked-circle-outline text-success"></i> Online'; }
		$attacks = $odb->query("SELECT COUNT(*) FROM `logs` WHERE `serveur` LIKE '%$ID%' AND `time` + `date` > UNIX_TIMESTAMP() AND `time` != 0")->fetchColumn(0);
		$load    = round($attacks / $getInfo['concurrents'] * 100, 2);
		echo '<tr>
				<td>' . $name . '</td>
				<td>
<div class="">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ' . $load . '%;" aria-valuenow="' . $load . '" aria-valuemin="0" aria-valuemax="'.$getInfo['concurrents'].'">' . $load . '%</div>
                                    </div>
                                </div>
</td>
				<td>' . $network . '</td>
				<td>'.$status.'</td>
			  </tr>';
	}
	
?>
 
    </tbody>
 </table>	
	